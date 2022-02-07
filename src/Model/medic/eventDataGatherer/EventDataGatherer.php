<?php

namespace HealthKerd\Model\medic\eventDataGatherer;

/** Classe de récupération du contenu des events suivant les eventID demandés */
class EventDataGatherer extends EventDataGathererPdoManager
{
    protected array $dataStore = array(); // Stockage de l'ensemble des données reçues depuis la DB
    protected array $eventsIdList = array(); // Liste des ID events

    protected object $medicORM; // Instance de l'ORM de la partie médicale


    public function __construct()
    {
        parent::__construct();
        $this->medicORM = new \HealthKerd\Model\medicORM\MedicORM();
    }


    public function __destruct()
    {
    }

    /** Lance la génération des déclarations SQL auprès de l'ORM en fonction des eventIDs avant de les rassembler puis de lancer une requete unique à la base
     * @param array $eventsIdList   Contient la liste des ID des events demandés
     * @return array                Contient toutes les données des events demandés
     */
    public function eventIdReceiver(array $eventsIdList)
    {
        //var_dump($eventsIdList);

        if (sizeof($eventsIdList) > 0) {
            // string injectée dans toutes les déclarations SQL pour indiquer tous les medic_event_list.medicEventID recherchés
            $whereString = $this->stmtWhereBuilder($eventsIdList, 'medic_event_list.medicEventID');

            // données liées à l'event
            $this->dataStore['medic_event_list']['pdoStmt'] = $this->medicORM->MedicEventList->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_affects_relation']['pdoStmt'] = $this->medicORM->MedicEventAffectsRelation->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_themes_relation']['pdoStmt'] = $this->medicORM->MedicEventThemesRelation->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_spemedic_relation']['pdoStmt'] = $this->medicORM->MedicEventSpemedicRelation->stmtSelectForEvent($whereString);

            // spécialités médicales des docteurs
            $this->dataStore['spe_medic_for_attended_docs_on_events']['pdoStmt'] = $this->medicORM->SpeMedicFullList->stmtSelectDistinctForAttendedDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_replaced_docs_on_events']['pdoStmt'] = $this->medicORM->SpeMedicFullList->stmtSelectDistinctForReplacedDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_attended_labo_ordo_docs_on_events']['pdoStmt'] = $this->medicORM->SpeMedicFullList->stmtSelectDistinctForLaboOrdoDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_replaced_labo_ordo_docs_on_events']['pdoStmt'] = $this->medicORM->SpeMedicFullList->stmtSelectDistinctForReplacedLaboOrdoDocsOnEvent($whereString);

            // données des docteurs
            $this->dataStore['doc_list_AttendedDoc']['pdoStmt'] = $this->medicORM->DocList->stmtSelectDistinctAttendedDocForEvent($whereString);
            $this->dataStore['doc_list_ReplacedDoc']['pdoStmt'] = $this->medicORM->DocList->stmtSelectDistinctReplacedDocForEvent($whereString);
            $this->dataStore['doc_list_LaboOrdoDoc']['pdoStmt'] = $this->medicORM->DocList->stmtSelectDistinctLaboOrdoDocForEvent($whereString);
            $this->dataStore['doc_list_LaboOrdoReplacedDoc']['pdoStmt'] = $this->medicORM->DocList->stmtSelectDistinctLaboOrdoReplacedDocForEvent($whereString);

            // données des diagnostics
            $this->dataStore['diag_list']['pdoStmt'] = $this->medicORM->DiagList->stmtSelectForEvent($whereString);
            $this->dataStore['diag_symptoms']['pdoStmt'] = $this->medicORM->DiagSymptoms->stmtSelectForEvent($whereString);
            $this->dataStore['diag_check_points']['pdoStmt'] = $this->medicORM->DiagCheckPoints->stmtSelectForEvent($whereString);
            $this->dataStore['diag_conclusions']['pdoStmt'] = $this->medicORM->DiagConclusions->stmtSelectForEvent($whereString);

            // ordonnances pharmacologiques et presciptions
            $this->dataStore['ordo_pharma_list']['pdoStmt'] = $this->medicORM->OrdoPharmaList->stmtSelectForEvent($whereString);
            $this->dataStore['presc_pharma_list']['pdoStmt'] = $this->medicORM->PrescPharmaList->stmtSelectForEvent($whereString);

            // ordonnances optiques
            $this->dataStore['ordo_sight_list']['pdoStmt'] = $this->medicORM->OrdoSightList->stmtSelectForEvent($whereString);

            // ordonnances de prélèvement en laboratoire médical et prescriptions
            $this->dataStore['ordo_labo_list']['pdoStmt'] = $this->medicORM->OrdoLaboList->stmtSelectForEvent($whereString);
            $this->dataStore['presc_labo_list']['pdoStmt'] = $this->medicORM->PrescLaboList->stmtSelectForEvent($whereString);
            $this->dataStore['presc_labo_elements']['pdoStmt'] = $this->medicORM->PrescLaboElements->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_labo_slots_for_diags']['pdoStmt'] = $this->medicORM->OrdoLaboSlots->stmtSelectForDiagsOnEvent($whereString);

            // ordonnances vaccinales
            $this->dataStore['ordo_vax_list']['pdoStmt'] = $this->medicORM->OrdoVaxList->stmtSelectForEvent($whereString);
            $this->dataStore['presc_vax_list']['pdoStmt'] = $this->medicORM->PrescVaxList->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_vax_slots_for_diags']['pdoStmt'] = $this->medicORM->OrdoVaxSlots->stmtSelectForDiagsOnEvent($whereString);

            // sessions de soin
            $this->dataStore['care_sessions_list']['pdoStmt'] = $this->medicORM->CareSessionsList->stmtSelectForEvent($whereString);
            $this->dataStore['care_session_elements']['pdoStmt'] = $this->medicORM->CareSessionElements->stmtSelectForEvent($whereString);

            // sessions de vaccination
            $this->dataStore['vax_sessions_list']['pdoStmt'] = $this->medicORM->VaxSessionsList->stmtSelectForDiagsOnEvent($whereString);
            $this->dataStore['vax_sessions_side_effects']['pdoStmt'] = $this->medicORM->VaxSessionsSideEffects->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_vax_slots_for_sessions']['pdoStmt'] = $this->medicORM->OrdoVaxSlots->stmtSelectForVaxSessionOnEvent($whereString);


            // Lancement de l'injection des données dans le $pdoBufferArray
            foreach ($this->dataStore as $key => $value) {
                $this->pdoStmtAndDestInsertionInCue($value['pdoStmt'], $key . '/pdoResult');
            }

            /** Utilisation des données stockées dans le pdoBufferArray
             * * Puis accés à la DB
             * * Puis écriture des données renvoyées dans $dataStore
            */
            $this->pdoQueryExec();
        }

        //echo '<pre>';
        //print_r($this->dataStore);
        //var_dump($this->dataStore);
        //echo '</pre>';

        return $this->dataStore;
    }
}
