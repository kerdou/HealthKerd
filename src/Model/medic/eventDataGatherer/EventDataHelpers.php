<?php

namespace HealthKerd\Model\medic\eventDataGatherer;

/** Model de la section home */
abstract class EventDataHelpers extends EventDataGathererPdoManager
{
    /** */
    public function __construct()
    {
        parent::__construct();
        $this->dataStoreArrayPrepPhase1();
        $this->dataStoreArrayPrepPhase2();
    }

    /** */
    private function dataStoreArrayPrepPhase1()
    {
        $this->dataStore['medic_event_list'] = array();
        $this->dataStore['medic_event_category'] = array();

        $this->dataStore['doc_list'] = array();
        $this->dataStore['doc_spemedic_relation'] = array();
        $this->dataStore['doc_office_list'] = array();

        $this->dataStore['spe_medic_full_list'] = array();

        $this->dataStore['medic_event_affects_relation'] = array();
        $this->dataStore['medic_event_themes_relation'] = array();
        $this->dataStore['medic_event_spemedic_relation'] = array();

        $this->dataStore['medic_theme_list'] = array();

        $this->dataStore['diag_list'] = array();
        $this->dataStore['diag_check_points'] = array();
        $this->dataStore['diag_medic_themes_relation'] = array();
        $this->dataStore['diag_conclusions'] = array();
        $this->dataStore['diag_symptoms'] = array();

        $this->dataStore['treat_pharma_sessions_list'] = array();
        $this->dataStore['treat_pharma_usage_phase'] = array();
        $this->dataStore['presc_pharma_list'] = array();
        $this->dataStore['treat_pharma_role_on_affect'] = array();
        $this->dataStore['ordo_pharma_list'] = array();
        $this->dataStore['treat_pharma_list'] = array();

        $this->dataStore['vax_sessions_list'] = array();
        $this->dataStore['vax_usage_phase'] = array();
        $this->dataStore['vax_sessions_side_effects'] = array();
        $this->dataStore['ordo_vax_slots'] = array();
        $this->dataStore['presc_vax_list'] = array();
        $this->dataStore['ordo_vax_list'] = array();

        $this->dataStore['labo_ordo_sampling_sessions_list'] = array();
        $this->dataStore['labo_noordo_sampling_sessions_list'] = array();
        $this->dataStore['labo_noordo_sections'] = array();
        $this->dataStore['ordo_labo_slots'] = array();
        $this->dataStore['labo_noordo_sections_elements'] = array();
        $this->dataStore['ordo_labo_list'] = array();
        $this->dataStore['presc_labo_list'] = array();
        $this->dataStore['presc_labo_elements'] = array();

        $this->dataStore['ordo_sight_list'] = array();

        $this->dataStore['care_sessions_list'] = array();
        $this->dataStore['care_usage_phase'] = array();
        $this->dataStore['care_role_on_affect'] = array();
        $this->dataStore['care_session_elements'] = array();

        $this->dataStore['medic_affect_list'] = array();
    }


    /** */
    private function dataStoreArrayPrepPhase2()
    {
        foreach ($this->dataStore as $key => $value) {
            $this->dataStore[$key]['extractedIdList'] = array();
            $this->dataStore[$key]['pdoStmt'] = '';
            $this->dataStore[$key]['pdoResult'] = array();
        }
    }


    /** */
    protected function gatherAll(string $tableName, array $idList, string $firstIdColumnName, string|null $secondIdColumnName = null)
    {
        if (sizeof($idList) > 0) {
            if (is_null($secondIdColumnName)) {
                $whereString = $this->stmtWhereBuilder($idList, $firstIdColumnName);
            } else {
                $creationWhereString = $this->stmtWhereBuilder($idList, $firstIdColumnName);
                $closureWhereString = $this->stmtWhereBuilder($idList, $secondIdColumnName);
                $whereArray = [$creationWhereString, $closureWhereString];
                $whereString = implode(' OR', $whereArray);
            }

            $stmt = 'SELECT * FROM ' . $tableName . ' WHERE ' . $whereString . '; ';
            $this->dataStore[$tableName]['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore[$tableName]['pdoStmt'], $tableName . '/pdoResult');
        }
    }


    /** */
    protected function dualColumnGatherAll(string $tableName, array $firstColumnIdList, string $firstColumnName, array $secondColumnIdList, string $secondColmunName)
    {
        $globalWhereArray = array();

        if (sizeof($firstColumnIdList) > 0) {
            $firstColmunWhereString = $this->stmtWhereBuilder($firstColumnIdList, $firstColumnName);
            array_push($globalWhereArray, $firstColmunWhereString);
        }

        if (sizeof($secondColumnIdList) > 0) {
            $secondColumnwhereString = $this->stmtWhereBuilder($secondColumnIdList, $secondColmunName);
            array_push($globalWhereArray, $secondColumnwhereString);
        }

        $whereString = implode(' OR', $globalWhereArray);


        if (strlen($whereString) > 0) {
            $stmt = 'SELECT * FROM ' . $tableName . ' WHERE ' . $whereString . '; ';
            $this->dataStore[$tableName]['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore[$tableName]['pdoStmt'], $tableName . '/pdoResult');
        }
    }


    /** */
    protected function gatherAllFromUsagePhase(string $tableName, array $eventsIdList, array $treatPharmaUsagePhaseID, string $phaseColumnName)
    {
        $eventsIdWhereString = '';
        $treatPharmaUsagePhasewhereString = '';
        $globalWhereArray = array();
        $whereString = '';


        if (sizeof($eventsIdList) > 0) {
            $creationWhereString = $this->stmtWhereBuilder($eventsIdList, 'creationMedicEventID');
            $closureWhereString = $this->stmtWhereBuilder($eventsIdList, 'closureMedicEventID');
            $whereArray = [$creationWhereString, $closureWhereString];
            $eventsIdWhereString = implode(' OR', $whereArray);
            array_push($globalWhereArray, $eventsIdWhereString);
        }


        if (sizeof($treatPharmaUsagePhaseID) > 0) {
            $treatPharmaUsagePhasewhereString = $this->stmtWhereBuilder($treatPharmaUsagePhaseID, $phaseColumnName);
            array_push($globalWhereArray, $treatPharmaUsagePhasewhereString);
        }

        $whereString = implode(' OR', $globalWhereArray);


        if (strlen($whereString) > 0) {
            $stmt = 'SELECT * FROM ' . $tableName . ' WHERE ' . $whereString . '; ';
            $this->dataStore[$tableName]['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore[$tableName]['pdoStmt'], $tableName . '/pdoResult');
        }
    }



    /** */
    protected function idExtractor(array $dataStoreSpot, string $searchedColmun)
    {
        $idList = array();

        foreach ($dataStoreSpot as $outerArray) {
            foreach ($outerArray as $innerKey => $innerValue) {
                if ($innerKey == $searchedColmun) {
                    array_push($idList, $innerValue);
                }
            }
        }

        $idList = array_unique($idList);
        sort($idList);
        $idList = array_map('intval', $idList);
        return $idList;
    }


    /** */
    protected function docIDExtractor()
    {
        $this->dataStore['medic_event_list']['extractedIdList']['unfilteredAllDocs'] = array();

        $this->dataStore['medic_event_list']['extractedIdList']['unfilteredAllDocs'] = array_merge(
            $this->dataStore['medic_event_list']['extractedIdList']['docID'],
            $this->dataStore['medic_event_list']['extractedIdList']['replacedDocID'],
            $this->dataStore['medic_event_list']['extractedIdList']['laboOrdoDocID'],
            $this->dataStore['medic_event_list']['extractedIdList']['laboOrdoReplacedDocDiagID']
        );

        $this->dataStore['medic_event_list']['extractedIdList']['unfilteredAllDocs'] =
            array_unique($this->dataStore['medic_event_list']['extractedIdList']['unfilteredAllDocs'], SORT_NUMERIC);

        sort($this->dataStore['medic_event_list']['extractedIdList']['unfilteredAllDocs'], SORT_NUMERIC);

        return $this->dataStore['medic_event_list']['extractedIdList']['unfilteredAllDocs'];
    }
}
