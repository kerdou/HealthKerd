<?php

namespace HealthKerd\Model\modelInit\medic\eventsFinderAndGatherer;

/** Classe de récupération du contenu des events suivant les eventID demandés */
class EventsSelectDataGatherModel extends \HealthKerd\Model\common\PdoBufferManager
{
    protected array $dataStore = array(); // Stockage de l'ensemble des données reçues depuis la DB

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Lance la génération des déclarations SQL auprès de l'ORM en fonction des eventIDs avant de les rassembler puis de lancer une requete unique à la base
     * @param array $eventsIdList   Contient la liste des ID des events demandés
     * @return array                Contient toutes les données des events demandés
     */
    public function eventDataGatherer(array $eventsIdList): array
    {
        $eventSelectMapper = new \HealthKerd\Model\tableMappers\medic\eventsFinderAndGatherer\EventsDataGatherSelectMapper();


        if (sizeof($eventsIdList) > 0) {
            // string injectée dans toutes les déclarations SQL pour indiquer tous les medic_event_list.medicEventID recherchés
            $whereString = $this->stmtWhereBuilder($eventsIdList, 'medic_event_list.medicEventID');

            // données liées à l'event
            $this->dataStore['medic_event_list']['pdoStmt'] = $eventSelectMapper->mapList['MedicEventList']->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_affects_relation']['pdoStmt'] = $eventSelectMapper->mapList['MedicEventAffectsRelation']->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_themes_relation']['pdoStmt'] = $eventSelectMapper->mapList['MedicEventThemesRelation']->stmtSelectForEvent($whereString);
            $this->dataStore['medic_event_spemedic_relation']['pdoStmt'] = $eventSelectMapper->mapList['MedicEventSpemedicRelation']->stmtSelectForEvent($whereString);

            // spécialités médicales des docteurs
            $this->dataStore['spe_medic_for_attended_docs_on_events']['pdoStmt'] = $eventSelectMapper->mapList['SpeMedicFullList']->stmtSelectDistinctForAttendedDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_replaced_docs_on_events']['pdoStmt'] = $eventSelectMapper->mapList['SpeMedicFullList']->stmtSelectDistinctForReplacedDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_attended_labo_ordo_docs_on_events']['pdoStmt'] = $eventSelectMapper->mapList['SpeMedicFullList']->stmtSelectDistinctForLaboOrdoDocsOnEvent($whereString);
            $this->dataStore['spe_medic_for_replaced_labo_ordo_docs_on_events']['pdoStmt'] = $eventSelectMapper->mapList['SpeMedicFullList']->stmtSelectDistinctForReplacedLaboOrdoDocsOnEvent($whereString);

            // données des docteurs
            $this->dataStore['doc_list_AttendedDoc']['pdoStmt'] = $eventSelectMapper->mapList['DocList']->stmtSelectDistinctAttendedDocForEvent($whereString);
            $this->dataStore['doc_list_ReplacedDoc']['pdoStmt'] = $eventSelectMapper->mapList['DocList']->stmtSelectDistinctReplacedDocForEvent($whereString);
            $this->dataStore['doc_list_LaboOrdoDoc']['pdoStmt'] = $eventSelectMapper->mapList['DocList']->stmtSelectDistinctLaboOrdoDocForEvent($whereString);
            $this->dataStore['doc_list_LaboOrdoReplacedDoc']['pdoStmt'] = $eventSelectMapper->mapList['DocList']->stmtSelectDistinctLaboOrdoReplacedDocForEvent($whereString);

            // données des diagnostics
            $this->dataStore['diag_list']['pdoStmt'] = $eventSelectMapper->mapList['DiagList']->stmtSelectForEvent($whereString);
            $this->dataStore['diag_symptoms']['pdoStmt'] = $eventSelectMapper->mapList['DiagSymptoms']->stmtSelectForEvent($whereString);
            $this->dataStore['diag_check_points']['pdoStmt'] = $eventSelectMapper->mapList['DiagCheckPoints']->stmtSelectForEvent($whereString);
            $this->dataStore['diag_conclusions']['pdoStmt'] = $eventSelectMapper->mapList['DiagConclusions']->stmtSelectForEvent($whereString);

            // ordonnances pharmacologiques et presciptions
            $this->dataStore['ordo_pharma_list']['pdoStmt'] = $eventSelectMapper->mapList['OrdoPharmaList']->stmtSelectForEvent($whereString);
            $this->dataStore['presc_pharma_list']['pdoStmt'] = $eventSelectMapper->mapList['PrescPharmaList']->stmtSelectForEvent($whereString);

            // ordonnances optiques
            $this->dataStore['ordo_sight_list']['pdoStmt'] = $eventSelectMapper->mapList['OrdoSightList']->stmtSelectForEvent($whereString);

            // ordonnances de prélèvement en laboratoire médical et prescriptions
            $this->dataStore['ordo_labo_list']['pdoStmt'] = $eventSelectMapper->mapList['OrdoLaboList']->stmtSelectForEvent($whereString);
            $this->dataStore['presc_labo_list']['pdoStmt'] = $eventSelectMapper->mapList['PrescLaboList']->stmtSelectForEvent($whereString);
            $this->dataStore['presc_labo_elements']['pdoStmt'] = $eventSelectMapper->mapList['PrescLaboElements']->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_labo_slots_for_diags']['pdoStmt'] = $eventSelectMapper->mapList['OrdoLaboSlots']->stmtSelectForDiagsOnEvent($whereString);

            // ordonnances vaccinales
            $this->dataStore['ordo_vax_list']['pdoStmt'] = $eventSelectMapper->mapList['OrdoVaxList']->stmtSelectForEvent($whereString);
            $this->dataStore['presc_vax_list']['pdoStmt'] = $eventSelectMapper->mapList['PrescVaxList']->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_vax_slots_for_diags']['pdoStmt'] = $eventSelectMapper->mapList['OrdoVaxSlots']->stmtSelectForDiagsOnEvent($whereString);

            // sessions de soin
            $this->dataStore['care_sessions_list']['pdoStmt'] = $eventSelectMapper->mapList['CareSessionsList']->stmtSelectForEvent($whereString);
            $this->dataStore['care_session_elements']['pdoStmt'] = $eventSelectMapper->mapList['CareSessionElements']->stmtSelectForEvent($whereString);

            // sessions de vaccination
            $this->dataStore['vax_sessions_list']['pdoStmt'] = $eventSelectMapper->mapList['VaxSessionsList']->stmtSelectForDiagsOnEvent($whereString);
            $this->dataStore['vax_sessions_side_effects']['pdoStmt'] = $eventSelectMapper->mapList['VaxSessionsSideEffects']->stmtSelectForEvent($whereString);
            $this->dataStore['ordo_vax_slots_for_sessions']['pdoStmt'] = $eventSelectMapper->mapList['OrdoVaxSlots']->stmtSelectForVaxSessionOnEvent($whereString);


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
