<?php

namespace HealthKerd\Model\medic\eventDataGatherer;

/** Classe de récupération du contenu des events suivant les eventID demandés */
class EventDataGatherer extends \HealthKerd\Model\common\PdoBufferManager
{
    protected array $dataStore = array(); // Stockage de l'ensemble des données reçues depuis la DB
    protected array $eventsIdList = array(); // Liste des ID events

    protected object $medicSqlStmtStore; // Instance du store de la partie médicale


    public function __construct()
    {
        parent::__construct();
        $this->medicSqlStmtStore = new \HealthKerd\Model\medicSqlStmtStore\MedicSqlStmtStore();
    }

    public function __destruct()
    {
    }

    /** Lance la génération des déclarations SQL auprès de l'ORM en fonction des eventIDs avant de les rassembler puis de lancer une requete unique à la base
     * @param array $eventsIdList   Contient la liste des ID des events demandés
     * @return array                Contient toutes les données des events demandés
     */
    public function eventIdReceiver(array $eventsIdList): array
    {
        //var_dump($eventsIdList);

        if (sizeof($eventsIdList) > 0) {
            // string injectée dans toutes les déclarations SQL pour indiquer tous les medic_event_list.medicEventID recherchés
            $whereString = $this->stmtWhereBuilder($eventsIdList, 'medic_event_list.medicEventID');

            // données liées à l'event
            $this->dataStore['medic_event_list']['pdoStmt'] = $this->medicSqlStmtStore->MedicEventList->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_affects_relation']['pdoStmt'] = $this->medicSqlStmtStore->MedicEventAffectsRelation->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_themes_relation']['pdoStmt'] = $this->medicSqlStmtStore->MedicEventThemesRelation->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_spemedic_relation']['pdoStmt'] = $this->medicSqlStmtStore->MedicEventSpemedicRelation->stmtSelectForEvent($whereString);

            // spécialités médicales des docteurs
            $this->dataStore['spe_medic_for_attended_docs_on_events']['pdoStmt'] = $this->medicSqlStmtStore->SpeMedicFullList->stmtSelectDistinctForAttendedDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_replaced_docs_on_events']['pdoStmt'] = $this->medicSqlStmtStore->SpeMedicFullList->stmtSelectDistinctForReplacedDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_attended_labo_ordo_docs_on_events']['pdoStmt'] = $this->medicSqlStmtStore->SpeMedicFullList->stmtSelectDistinctForLaboOrdoDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_replaced_labo_ordo_docs_on_events']['pdoStmt'] = $this->medicSqlStmtStore->SpeMedicFullList->stmtSelectDistinctForReplacedLaboOrdoDocsOnEvent($whereString);

            // données des docteurs
            $this->dataStore['doc_list_AttendedDoc']['pdoStmt'] = $this->medicSqlStmtStore->DocList->stmtSelectDistinctAttendedDocForEvent($whereString);
            $this->dataStore['doc_list_ReplacedDoc']['pdoStmt'] = $this->medicSqlStmtStore->DocList->stmtSelectDistinctReplacedDocForEvent($whereString);
            $this->dataStore['doc_list_LaboOrdoDoc']['pdoStmt'] = $this->medicSqlStmtStore->DocList->stmtSelectDistinctLaboOrdoDocForEvent($whereString);
            $this->dataStore['doc_list_LaboOrdoReplacedDoc']['pdoStmt'] = $this->medicSqlStmtStore->DocList->stmtSelectDistinctLaboOrdoReplacedDocForEvent($whereString);

            // données des diagnostics
            $this->dataStore['diag_list']['pdoStmt'] = $this->medicSqlStmtStore->DiagList->stmtSelectForEvent($whereString);
            $this->dataStore['diag_symptoms']['pdoStmt'] = $this->medicSqlStmtStore->DiagSymptoms->stmtSelectForEvent($whereString);
            $this->dataStore['diag_check_points']['pdoStmt'] = $this->medicSqlStmtStore->DiagCheckPoints->stmtSelectForEvent($whereString);
            $this->dataStore['diag_conclusions']['pdoStmt'] = $this->medicSqlStmtStore->DiagConclusions->stmtSelectForEvent($whereString);

            // ordonnances pharmacologiques et presciptions
            $this->dataStore['ordo_pharma_list']['pdoStmt'] = $this->medicSqlStmtStore->OrdoPharmaList->stmtSelectForEvent($whereString);
            $this->dataStore['presc_pharma_list']['pdoStmt'] = $this->medicSqlStmtStore->PrescPharmaList->stmtSelectForEvent($whereString);

            // ordonnances optiques
            $this->dataStore['ordo_sight_list']['pdoStmt'] = $this->medicSqlStmtStore->OrdoSightList->stmtSelectForEvent($whereString);

            // ordonnances de prélèvement en laboratoire médical et prescriptions
            $this->dataStore['ordo_labo_list']['pdoStmt'] = $this->medicSqlStmtStore->OrdoLaboList->stmtSelectForEvent($whereString);
            $this->dataStore['presc_labo_list']['pdoStmt'] = $this->medicSqlStmtStore->PrescLaboList->stmtSelectForEvent($whereString);
            $this->dataStore['presc_labo_elements']['pdoStmt'] = $this->medicSqlStmtStore->PrescLaboElements->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_labo_slots_for_diags']['pdoStmt'] = $this->medicSqlStmtStore->OrdoLaboSlots->stmtSelectForDiagsOnEvent($whereString);

            // ordonnances vaccinales
            $this->dataStore['ordo_vax_list']['pdoStmt'] = $this->medicSqlStmtStore->OrdoVaxList->stmtSelectForEvent($whereString);
            $this->dataStore['presc_vax_list']['pdoStmt'] = $this->medicSqlStmtStore->PrescVaxList->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_vax_slots_for_diags']['pdoStmt'] = $this->medicSqlStmtStore->OrdoVaxSlots->stmtSelectForDiagsOnEvent($whereString);

            // sessions de soin
            $this->dataStore['care_sessions_list']['pdoStmt'] = $this->medicSqlStmtStore->CareSessionsList->stmtSelectForEvent($whereString);
            $this->dataStore['care_session_elements']['pdoStmt'] = $this->medicSqlStmtStore->CareSessionElements->stmtSelectForEvent($whereString);

            // sessions de vaccination
            $this->dataStore['vax_sessions_list']['pdoStmt'] = $this->medicSqlStmtStore->VaxSessionsList->stmtSelectForDiagsOnEvent($whereString);
            $this->dataStore['vax_sessions_side_effects']['pdoStmt'] = $this->medicSqlStmtStore->VaxSessionsSideEffects->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_vax_slots_for_sessions']['pdoStmt'] = $this->medicSqlStmtStore->OrdoVaxSlots->stmtSelectForVaxSessionOnEvent($whereString);


            // Lancement de l'injection des données dans le $pdoBufferArray
            foreach ($this->dataStore as $key => $value) {
                $this->pdoStmtAndDestInsertionInCue($value['pdoStmt'], $key . '/pdoResult');
            }

            /** Utilisation des données stockées dans le pdoBufferArray
             * * Puis accés à la DB
             * * Puis écriture des données renvoyées dans $dataStore
            */
            $dataToWrite = array();
            $dataToWrite = $this->pdoQueryExec();
            $this->pdoResultWriter($dataToWrite);
        }

        //echo '<pre>';
        //  print_r($this->dataStore);
        //  var_dump($this->dataStore);
        //echo '</pre>';

        return $this->dataStore;
    }

    /** Placement des données à l'endroit voulu dans $dataStore
     * @param array $dataToWrite    Données provenants du Pdo Buffer à écrire dans $dataStore
     */
    private function pdoResultWriter(array $dataToWrite): void
    {
        foreach ($dataToWrite['dest'] as $destKey => $destValue) {
            switch (count($destValue)) {
                case 0:
                    $this->dataStore['unexpectedDest'] = array();
                    array_push($this->dataStore['unexpectedDest'], $dataToWrite['pdoMixedResult'][$destKey]);
                    break;

                case 1:
                    $this->dataStore[$destValue[0]] = $dataToWrite['pdoMixedResult'][$destKey];
                    break;

                case 2:
                    $this->dataStore[$destValue[0]][$destValue[1]] = $dataToWrite['pdoMixedResult'][$destKey];
                    break;

                case 3:
                    $this->dataStore[$destValue[0]][$destValue[1]][$destValue[2]] = $dataToWrite['pdoMixedResult'][$destKey];
                    break;

                default:
                    $this->dataStore['unexpectedDest'] = array();
                    array_push($this->dataStore['unexpectedDest'], $dataToWrite['pdoMixedResult'][$destKey]);
                    break;
            }
        }
    }
}
