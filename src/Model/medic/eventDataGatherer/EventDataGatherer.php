<?php

namespace HealthKerd\Model\medic\eventDataGatherer;

/** Model de la section home */
class EventDataGatherer extends SpecificStmtStore
{
    protected array $dataStore = array(); // Stockage de l'ensemble des données reçues depuis la DB
    protected array $eventsIdList = array(); // liste des ID events

    public function __construct()
    {
        parent::__construct();
    }

    /** 41 tables contactées en 7 passes */
    public function eventIdReceiver(array $eventsIdList)
    {
        //var_dump($eventsIdList);

        if (sizeof($eventsIdList) > 0) {
            $this->gatherWave1PdoPrep($eventsIdList);
            $this->pdoQueryExec();

            $this->idExtractionFromWave1();
            $this->gatherWave2PdoPrep();
            $this->pdoQueryExec();

            $this->idExtractionFromWave2();
            $this->gatherWave3PdoPrep();
            $this->pdoQueryExec();

            $this->idExtractionFromWave3();
            $this->gatherWave4PdoPrep();
            $this->pdoQueryExec();

            $this->idExtractionFromWave4();
            $this->gatherWave5PdoPrep();
            $this->pdoQueryExec();

            $this->idExtractionFromWave5();
            $this->gatherWave6PdoPrep();
            $this->pdoQueryExec();

            $this->idExtractionFromWave6();
            $this->gatherWave7PdoPrep();
            $this->pdoQueryExec();
        }

        //echo '<pre>';
        //print_r($this->dataStore);
        //var_dump($this->dataStore);
        //echo '</pre>';

        return $this->dataStore;
    }


    /** */
    private function gatherWave1PdoPrep($eventsIdList)
    {
        $this->eventsIdList = $eventsIdList;
        // medic events
        $this->medicEventList($eventsIdList);

        // relations
        $this->medicEventAffectsRelation($eventsIdList);
        $this->medicEventThemesRelation($eventsIdList);
        $this->medicEventSpemedicRelation($eventsIdList);

        // diag
        $this->gatherAll('diag_list', $eventsIdList, 'medicEventID');

        // treat pharma
        $this->gatherAll('treat_pharma_sessions_list', $eventsIdList, 'medicEventID');

        // vax
        $this->gatherAll('vax_sessions_list', $eventsIdList, 'medicEventID');

        // labo
        $this->gatherAll('labo_ordo_sampling_sessions_list', $eventsIdList, 'medicEventID');
        $this->gatherAll('labo_noordo_sampling_sessions_list', $eventsIdList, 'medicEventID');

        // care
        $this->gatherAll('care_sessions_list', $eventsIdList, 'medicEventID');
    }


    /** */
    private function idExtractionFromWave1()
    {
        // medic_event_list
        $this->dataStore['medic_event_list']['extractedIdList']['medicEventCatID'] = $this->idExtractor($this->dataStore['medic_event_list']['pdoResult'], 'medicEventCatID');
        $this->dataStore['medic_event_list']['extractedIdList']['docID'] = $this->idExtractor($this->dataStore['medic_event_list']['pdoResult'], 'docID');
        $this->dataStore['medic_event_list']['extractedIdList']['replacedDocID'] = $this->idExtractor($this->dataStore['medic_event_list']['pdoResult'], 'replacedDocID');
        $this->dataStore['medic_event_list']['extractedIdList']['laboOrdoDocID'] = $this->idExtractor($this->dataStore['medic_event_list']['pdoResult'], 'laboOrdoDocID');
        $this->dataStore['medic_event_list']['extractedIdList']['laboOrdoReplacedDocDiagID'] = $this->idExtractor($this->dataStore['medic_event_list']['pdoResult'], 'laboOrdoReplacedDocDiagID');
        $this->dataStore['medic_event_list']['extractedIdList']['allUniqueDocs'] = $this->docIDExtractor(); // REGROUPEMENT DES ID DES DOCS!!!!
        $this->dataStore['medic_event_list']['extractedIdList']['docOfficeID'] = $this->idExtractor($this->dataStore['medic_event_list']['pdoResult'], 'docOfficeID');

        // medic_event_affects_relation
        $this->dataStore['medic_event_affects_relation']['extractedIdList']['medicAffectID'] = $this->idExtractor($this->dataStore['medic_event_affects_relation']['pdoResult'], 'medicAffectID');

        // medic_event_themes_relation
        $this->dataStore['medic_event_themes_relation']['extractedIdList']['medicThemeID'] = $this->idExtractor($this->dataStore['medic_event_themes_relation']['pdoResult'], 'medicThemeID');

        // medic_event_spemedic_relation
        $this->dataStore['medic_event_spemedic_relation']['extractedIdList']['speMedicID'] = $this->idExtractor($this->dataStore['medic_event_spemedic_relation']['pdoResult'], 'speMedicID');

        // diag_list
        $this->dataStore['diag_list']['extractedIdList']['diagID'] = $this->idExtractor($this->dataStore['diag_list']['pdoResult'], 'diagID');

        // treat_pharma_usage_phase
        $this->dataStore['treat_pharma_usage_phase']['extractedIdList']['treatPharmaUsagePhaseID'] = $this->idExtractor($this->dataStore['treat_pharma_usage_phase']['pdoResult'], 'treatPharmaUsagePhaseID');

        // vax_sessions_list
        $this->dataStore['vax_sessions_list']['extractedIdList']['vaxSessionID'] = $this->idExtractor($this->dataStore['vax_sessions_list']['pdoResult'], 'vaxSessionID');
        $this->dataStore['vax_sessions_list']['extractedIdList']['vaxUsagePhaseID'] = $this->idExtractor($this->dataStore['vax_sessions_list']['pdoResult'], 'vaxUsagePhaseID');

        // labo_ordo_sampling_sessions_list
        $this->dataStore['labo_ordo_sampling_sessions_list']['extractedIdList']['laboOrdoSamplingSessionID'] = $this->idExtractor($this->dataStore['labo_ordo_sampling_sessions_list']['pdoResult'], 'laboOrdoSamplingSessionID');

        // labo_noordo_sampling_sessions_list
        $this->dataStore['labo_noordo_sampling_sessions_list']['extractedIdList']['laboNoOrdoSamplingSessionID'] = $this->idExtractor($this->dataStore['labo_noordo_sampling_sessions_list']['pdoResult'], 'laboNoOrdoSamplingSessionID');

        // care_sessions_list
        $this->dataStore['care_sessions_list']['extractedIdList']['careSessionID'] = $this->idExtractor($this->dataStore['care_sessions_list']['pdoResult'], 'careSessionID');
        $this->dataStore['care_sessions_list']['extractedIdList']['careUsagePhaseID'] = $this->idExtractor($this->dataStore['care_sessions_list']['pdoResult'], 'careUsagePhaseID');
    }


    /** */
    private function gatherWave2PdoPrep()
    {
        // medic_event_category
        $this->gatherAll('medic_event_category', $this->dataStore['medic_event_list']['extractedIdList']['medicEventCatID'], 'medicEventCatID');

        // doc_list & doc_spemedic_relation & doc_office_list
        $this->docList($this->dataStore['medic_event_list']['extractedIdList']['allUniqueDocs']); // doc_list
        $this->gatherAll('doc_spemedic_relation', $this->dataStore['medic_event_list']['extractedIdList']['allUniqueDocs'], 'docID');
        $this->docOfficeList($this->dataStore['medic_event_list']['extractedIdList']['docOfficeID']); // doc_office_list

        // diag_check_points & diag_medic_themes_relation & diag_symptoms & diag_conclusions
        $this->gatherAll('diag_check_points', $this->dataStore['diag_list']['extractedIdList']['diagID'], 'diagID');
        $this->gatherAll('diag_medic_themes_relation', $this->dataStore['diag_list']['extractedIdList']['diagID'], 'diagID');
        $this->gatherAll('diag_symptoms', $this->dataStore['diag_list']['extractedIdList']['diagID'], 'diagID');
        $this->gatherAll('diag_conclusions', $this->dataStore['diag_list']['extractedIdList']['diagID'], 'diagID');

        // treat_pharma_usage_phase
        $this->gatherAllFromUsagePhase(
            'treat_pharma_usage_phase',
            $this->eventsIdList,
            $this->dataStore['treat_pharma_usage_phase']['extractedIdList']['treatPharmaUsagePhaseID'],
            'treatPharmaUsagePhaseID'
        );

        // treat_pharma_usage_phase
        $this->gatherAllFromUsagePhase(
            'vax_usage_phase',
            $this->eventsIdList,
            $this->dataStore['vax_sessions_list']['extractedIdList']['vaxUsagePhaseID'],
            'vaxUsagePhaseID'
        );

        // labo_noordo_sections & ordo_labo_slots
        $this->gatherAll('labo_noordo_sections', $this->dataStore['labo_noordo_sampling_sessions_list']['extractedIdList']['laboNoOrdoSamplingSessionID'], 'laboNoOrdoSamplingSessionID');
        $this->gatherAll('ordo_labo_slots', $this->dataStore['labo_ordo_sampling_sessions_list']['extractedIdList']['laboOrdoSamplingSessionID'], 'laboOrdoSamplingSessionID');

        // ordo_sight_list
        $this->gatherAll('ordo_sight_list', $this->dataStore['diag_list']['extractedIdList']['diagID'], 'diagID');

        // care_usage_phase
        $this->gatherAllFromUsagePhase(
            'care_usage_phase',
            $this->eventsIdList,
            $this->dataStore['care_sessions_list']['extractedIdList']['careUsagePhaseID'],
            'careUsagePhaseID'
        );
    }


    /** */
    private function idExtractionFromWave2()
    {
        // doc_spemedic_relation
        $this->dataStore['doc_spemedic_relation']['extractedIdList']['speMedicID'] = $this->idExtractor($this->dataStore['doc_spemedic_relation']['pdoResult'], 'speMedicID');

        // diag_medic_themes_relation
        $this->dataStore['diag_medic_themes_relation']['extractedIdList']['medicThemeID'] = $this->idExtractor($this->dataStore['diag_medic_themes_relation']['pdoResult'], 'medicThemeID');

        // diag_conclusions
        $this->dataStore['diag_conclusions']['extractedIdList']['medicAffectID'] = $this->idExtractor($this->dataStore['diag_conclusions']['pdoResult'], 'medicAffectID');

        // treat_pharma_usage_phase
        $this->dataStore['treat_pharma_usage_phase']['extractedIdList']['treatPharmaID'] = $this->idExtractor($this->dataStore['treat_pharma_usage_phase']['pdoResult'], 'treatPharmaID');
        $this->dataStore['treat_pharma_usage_phase']['extractedIdList']['treatPharmaRoleID'] = $this->idExtractor($this->dataStore['treat_pharma_usage_phase']['pdoResult'], 'treatPharmaRoleID');
        $this->dataStore['treat_pharma_usage_phase']['extractedIdList']['prescPharmaID'] = $this->idExtractor($this->dataStore['treat_pharma_usage_phase']['pdoResult'], 'prescPharmaID');

        // vax_usage_phase
        $this->dataStore['vax_usage_phase']['extractedIdList']['medicAffectID'] = $this->idExtractor($this->dataStore['vax_usage_phase']['pdoResult'], 'medicAffectID');
        $this->dataStore['vax_usage_phase']['extractedIdList']['prescVaxID'] = $this->idExtractor($this->dataStore['vax_usage_phase']['pdoResult'], 'prescVaxID');
        $this->dataStore['vax_usage_phase']['extractedIdList']['treatPharmaID'] = $this->idExtractor($this->dataStore['vax_usage_phase']['pdoResult'], 'treatPharmaID');

        // labo_noordo_sections
        $this->dataStore['labo_noordo_sections']['extractedIdList']['laboNoOrdoSectionID'] = $this->idExtractor($this->dataStore['labo_noordo_sections']['pdoResult'], 'laboNoOrdoSectionID');

        // ordo_labo_slots
        $this->dataStore['ordo_labo_slots']['extractedIdList']['ordoLaboID'] = $this->idExtractor($this->dataStore['ordo_labo_slots']['pdoResult'], 'ordoLaboID');

        // care_usage_phase
        $this->dataStore['care_usage_phase']['extractedIdList']['careRoleOnAffectID'] = $this->idExtractor($this->dataStore['care_usage_phase']['pdoResult'], 'careRoleOnAffectID');
    }


    /** */
    private function gatherWave3PdoPrep()
    {
        // spe_medic_full_list
        $this->gatherAll('spe_medic_full_list', $this->dataStore['doc_spemedic_relation']['extractedIdList']['speMedicID'], 'speMedicID');

        // medic_theme_list
        $this->gatherAll('medic_theme_list', $this->dataStore['medic_event_themes_relation']['extractedIdList']['medicThemeID'], 'medicThemeID');

        // presc_pharma_list
        $this->prescPharmaListOnlyOrdoPharmaIds($this->dataStore['treat_pharma_usage_phase']['extractedIdList']['prescPharmaID']);

        // vax_sessions_side_effects
        $this->gatherAll('vax_sessions_side_effects', $this->dataStore['vax_sessions_list']['extractedIdList']['vaxSessionID'], 'vaxSessionID');

        // ordo_vax_slots
        $this->gatherAll('ordo_vax_slots', $this->dataStore['vax_sessions_list']['extractedIdList']['vaxSessionID'], 'vaxSessionID');

        // presc_vax_list
        $this->ordoVaxSlotsOnlyOrdoVaxIds($this->dataStore['vax_usage_phase']['extractedIdList']['prescVaxID']);

        // labo_noordo_sections_elements
        $this->gatherAll('labo_noordo_sections_elements', $this->dataStore['labo_noordo_sections']['extractedIdList']['laboNoOrdoSectionID'], 'laboNoOrdoSectionID');

        // ordo_labo_list
        $this->dualColumnGatherAll(
            'ordo_labo_list',
            $this->dataStore['diag_list']['extractedIdList']['diagID'],
            'diagID',
            $this->dataStore['ordo_labo_slots']['extractedIdList']['ordoLaboID'],
            'ordoLaboID'
        );

        // care_role_on_affect
        $this->gatherAll('care_role_on_affect', $this->dataStore['care_usage_phase']['extractedIdList']['careRoleOnAffectID'], 'careRoleOnAffectID');

        // care_session_elements
        $this->gatherAll('care_session_elements', $this->dataStore['care_sessions_list']['extractedIdList']['careSessionID'], 'careSessionID');
    }


    /** */
    private function idExtractionFromWave3()
    {
        // presc_pharma_list
        $this->dataStore['presc_pharma_list']['extractedIdList']['ordoPharmaID'] = $this->idExtractor($this->dataStore['presc_pharma_list']['pdoResult'], 'ordoPharmaID');

        // ordo_vax_slots
        $this->dataStore['ordo_vax_slots']['extractedIdList']['ordoVaxID'] = $this->idExtractor($this->dataStore['ordo_vax_slots']['pdoResult'], 'ordoVaxID');

        // presc_vax_list
        $this->dataStore['presc_vax_list']['extractedIdList']['ordoVaxID'] = $this->idExtractor($this->dataStore['presc_vax_list']['pdoResult'], 'ordoVaxID');

        // presc_vax_list
        $this->dataStore['ordo_labo_list']['extractedIdList']['ordoLaboID'] = $this->idExtractor($this->dataStore['ordo_labo_list']['pdoResult'], 'ordoLaboID');

        // care_role_on_affect
        $this->dataStore['care_role_on_affect']['extractedIdList']['medicAffectID'] = $this->idExtractor($this->dataStore['care_role_on_affect']['pdoResult'], 'medicAffectID');
    }



    /** */
    private function gatherWave4PdoPrep()
    {
        // ordo_pharma_list
        $this->dualColumnGatherAll(
            'ordo_pharma_list',
            $this->dataStore['diag_list']['extractedIdList']['diagID'],
            'diagID',
            $this->dataStore['presc_pharma_list']['extractedIdList']['ordoPharmaID'],
            'ordoPharmaID'
        );

        // ordo_vax_list
        $ordoVaxIDList = array_merge(
            $this->dataStore['ordo_vax_slots']['extractedIdList']['ordoVaxID'],
            $this->dataStore['presc_vax_list']['extractedIdList']['ordoVaxID']
        );
        $ordoVaxIDList = array_unique($ordoVaxIDList, SORT_NUMERIC);

        $this->dualColumnGatherAll(
            'ordo_vax_list',
            $this->dataStore['diag_list']['extractedIdList']['diagID'],
            'diagID',
            $ordoVaxIDList,
            'ordoVaxID'
        );

        // presc_labo_list
        $this->gatherAll('presc_labo_list', $this->dataStore['ordo_labo_list']['extractedIdList']['ordoLaboID'], 'ordoLaboID');
    }


    /** */
    private function idExtractionFromWave4()
    {
        // ordo_pharma_list
        $this->dataStore['ordo_pharma_list']['extractedIdList']['ordoPharmaID'] = $this->idExtractor($this->dataStore['ordo_pharma_list']['pdoResult'], 'ordoPharmaID');

        // ordo_vax_list
        $this->dataStore['ordo_vax_list']['extractedIdList']['ordoVaxID'] = $this->idExtractor($this->dataStore['ordo_vax_list']['pdoResult'], 'ordoVaxID');

        // presc_labo_list
        $this->dataStore['presc_labo_list']['extractedIdList']['prescLaboID'] = $this->idExtractor($this->dataStore['presc_labo_list']['pdoResult'], 'prescLaboID');
    }


    /** */
    private function gatherWave5PdoPrep()
    {
        // presc_pharma_list
        $this->dualColumnGatherAll(
            'presc_pharma_list',
            $this->dataStore['ordo_pharma_list']['extractedIdList']['ordoPharmaID'],
            'ordoPharmaID',
            $this->dataStore['treat_pharma_usage_phase']['extractedIdList']['prescPharmaID'],
            'prescPharmaID'
        );

        // ordo_vax_slots
        $this->dualColumnGatherAll(
            'ordo_vax_slots',
            $this->dataStore['vax_sessions_list']['extractedIdList']['vaxSessionID'],
            'vaxSessionID',
            $this->dataStore['ordo_vax_list']['extractedIdList']['ordoVaxID'],
            'ordoVaxID'
        );

        // presc_vax_list
        $this->dualColumnGatherAll(
            'presc_vax_list',
            $this->dataStore['vax_usage_phase']['extractedIdList']['prescVaxID'],
            'prescVaxID',
            $this->dataStore['ordo_vax_list']['extractedIdList']['ordoVaxID'],
            'ordoVaxID'
        );

        // presc_labo_elements
        $this->gatherAll('presc_labo_elements', $this->dataStore['presc_labo_list']['extractedIdList']['prescLaboID'], 'prescLaboID');
    }


    /** */
    private function idExtractionFromWave5()
    {
        // presc_pharma_list
        $this->dataStore['presc_pharma_list']['extractedIdList']['treatPharmaID'] = $this->idExtractor($this->dataStore['presc_pharma_list']['pdoResult'], 'treatPharmaID');
        $this->dataStore['presc_pharma_list']['extractedIdList']['treatPharmaRoleID'] = $this->idExtractor($this->dataStore['presc_pharma_list']['pdoResult'], 'treatPharmaRoleID');

        // presc_vax_list
        $this->dataStore['presc_vax_list']['extractedIdList']['treatPharmaID'] = $this->idExtractor($this->dataStore['presc_vax_list']['pdoResult'], 'treatPharmaID');
    }


    /** */
    private function gatherWave6PdoPrep()
    {
        // treat_pharma_role_on_affect
        $treatPharmaRoleID = array_merge(
            $this->dataStore['treat_pharma_usage_phase']['extractedIdList']['treatPharmaRoleID'],
            $this->dataStore['presc_pharma_list']['extractedIdList']['treatPharmaRoleID']
        );
        $treatPharmaRoleID = array_unique($treatPharmaRoleID, SORT_NUMERIC);
        $this->gatherAll('treat_pharma_role_on_affect', $treatPharmaRoleID, 'treatPharmaRoleID');

        // treat_pharma_list
        $treatPharmaID = array_merge(
            $this->dataStore['treat_pharma_usage_phase']['extractedIdList']['treatPharmaID'],
            $this->dataStore['presc_vax_list']['extractedIdList']['treatPharmaID'],
            $this->dataStore['presc_pharma_list']['extractedIdList']['treatPharmaID'],
            $this->dataStore['vax_usage_phase']['extractedIdList']['treatPharmaID']
        );
        $treatPharmaID = array_unique($treatPharmaID, SORT_NUMERIC);
        $this->treatPharmaNames($treatPharmaID);
    }


    /** */
    private function idExtractionFromWave6()
    {
        // treat_pharma_role_on_affect
        $this->dataStore['treat_pharma_role_on_affect']['extractedIdList']['medicAffectID'] = $this->idExtractor($this->dataStore['treat_pharma_role_on_affect']['pdoResult'], 'medicAffectID');
    }

    /** */
    private function gatherWave7PdoPrep()
    {
        // medic_affect_list
        $medicAffectID = array_merge(
            $this->dataStore['treat_pharma_role_on_affect']['extractedIdList']['medicAffectID'],
            $this->dataStore['care_role_on_affect']['extractedIdList']['medicAffectID'],
            $this->dataStore['vax_usage_phase']['extractedIdList']['medicAffectID'],
            $this->dataStore['diag_conclusions']['extractedIdList']['medicAffectID'],
        );
        $medicAffectID = array_unique($medicAffectID, SORT_NUMERIC);
        $this->medicAffectNames($medicAffectID);
    }
}
