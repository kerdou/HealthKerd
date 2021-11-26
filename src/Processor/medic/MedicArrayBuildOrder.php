<?php

namespace HealthKerd\Processor\medic;

class MedicArrayBuildOrder
{
    private array $originalDataStore = array();
    private array|null $dataWorkbench = array();
    private array|null $objectStore = array();
    private array|null $processedDataArray = array();

    /**
     *
     */
    public function __construct()
    {
        $this->dataWorkbench['dateAndTime'] = array();
        $this->dateAndTimeCreator();

        $this->dataWorkbench['eventArray'] = array();
        $this->dataWorkbench['eventFinalArray'] = array();

        $this->dataWorkbench['prescOrdoLabo'] = array();
        $this->dataWorkbench['prescOrdoPharma'] = array();
        $this->dataWorkbench['prescOrdoVax'] = array();

        $this->dataWorkbench['ordoLabo'] = array();
        $this->dataWorkbench['ordoPharma'] = array();
        $this->dataWorkbench['ordoVax'] = array();
        $this->dataWorkbench['ordoSight'] = array();
        $this->dataWorkbench['ordoGlobalArray'] = array();

        $this->dataWorkbench['diagThemes'] = array();
        $this->dataWorkbench['diagConclusions'] = array();
        $this->dataWorkbench['diagList'] = array();

        $this->dataWorkbench['careSessions'] = array();

        $this->dataWorkbench['vaxSessions'] = array();

        $this->objectStore['EventDataOrganizer'] = new \HealthKerd\Processor\medic\event\EventDataOrganizer();
        $this->objectStore['EventFinalContentMerger'] = new \HealthKerd\Processor\medic\event\EventFinalContentMerger();

        $this->objectStore['PrescOrdoLaboOrganizer'] = new \HealthKerd\Processor\medic\presc\PrescOrdoLaboOrganizer();
        $this->objectStore['PrescOrdoPharmaOrganizer'] = new \HealthKerd\Processor\medic\presc\PrescOrdoPharmaOrganizer();
        $this->objectStore['PrescOrdoVaxOrganizer'] = new \HealthKerd\Processor\medic\presc\PrescOrdoVaxOrganizer();

        $this->objectStore['OrdoLaboOrganizer'] = new \HealthKerd\Processor\medic\ordo\OrdoLaboOrganizer();
        $this->objectStore['OrdoPharmaOrganizer'] = new \HealthKerd\Processor\medic\ordo\OrdoPharmaOrganizer();
        $this->objectStore['OrdoVaxOrganizer'] = new \HealthKerd\Processor\medic\ordo\OrdoVaxOrganizer();
        $this->objectStore['OrdoSightOrganizer'] = new \HealthKerd\Processor\medic\ordo\OrdoSightOrganizer();
        $this->objectStore['OrdoGatherAndSorting'] = new \HealthKerd\Processor\medic\ordo\OrdoGatherAndSorting();

        $this->objectStore['DiagThemesOrganizer'] = new \HealthKerd\Processor\medic\diag\DiagThemesOrganizer();
        $this->objectStore['DiagConclusionsOrganizer'] = new \HealthKerd\Processor\medic\diag\DiagConclusionsOrganizer();
        $this->objectStore['DiagListOrganizer'] = new \HealthKerd\Processor\medic\diag\DiagListOrganizer();

        $this->objectStore['CareSessionsOrganizer'] = new \HealthKerd\Processor\medic\care\CareSessionsOrganizer();

        $this->objectStore['VaxSessionsOrganizer'] = new \HealthKerd\Processor\medic\vax\VaxSessionsOrganizer();
    }


    /**
     *
     */
    public function eventDataReceiver(array $receivedDataStore)
    {
        $this->originalDataStore = $receivedDataStore;

        $this->eventStartingManager();

        $this->prescManager();
        $this->ordoManager();
        $this->diagManager();
        $this->careSessionsManager();
        $this->vaxSessionsManager();

        $this->eventEndingManager();

        //echo '<pre>';
        //print_r($this->dataWorkbench['eventFinalArray']);
        //echo '</pre>';

        unset($receivedDataStore); // suppression des références au tableau
        $receivedDataStore = array(); // suppression du contenu du tableau

        unset($this->originalDataStore);
        $this->originalDataStore = array();

        $this->processedDataArray = $this->dataWorkbench['eventFinalArray'];

        unset($this->dataWorkbench);
        $this->dataWorkbench = array();

        unset($this->objectStore);
        $this->objectStore = array();

        return $this->processedDataArray;
    }


    /** Création et stockage de toutes les données temporelles
     * @param array $dateAndTime Stocke toutes les données temporelles
     */
    private function dateAndTimeCreator()
    {
        setlocale(LC_TIME, 'fr_FR', 'fra');
        $this->dataWorkbench['dateAndTime']['timezoneObj'] = timezone_open('Europe/Paris');
        $this->dataWorkbench['dateAndTime']['nowTimeObj'] = date_create('now', $this->dataWorkbench['dateAndTime']['timezoneObj']);

        $this->dataWorkbench['dateAndTime']['todayEarlyTimeObj'] = date_time_set($this->dataWorkbench['dateAndTime']['nowTimeObj'], 0, 0, 0, 0);
        $this->dataWorkbench['dateAndTime']['todayEarlyTimeOffset'] = date_offset_get($this->dataWorkbench['dateAndTime']['todayEarlyTimeObj']);
        $this->dataWorkbench['dateAndTime']['todayEarlyTimestamp'] = date_timestamp_get($this->dataWorkbench['dateAndTime']['todayEarlyTimeObj']) + $this->dataWorkbench['dateAndTime']['todayEarlyTimeOffset'];

        $this->dataWorkbench['dateAndTime']['todayLateTimeObj'] = date_time_set($this->dataWorkbench['dateAndTime']['nowTimeObj'], 23, 59, 59, 999999);
        $this->dataWorkbench['dateAndTime']['todayLateTimeOffset'] = date_offset_get($this->dataWorkbench['dateAndTime']['todayLateTimeObj']);
        $this->dataWorkbench['dateAndTime']['todayLateTimestamp'] = date_timestamp_get($this->dataWorkbench['dateAndTime']['todayLateTimeObj'])  + $this->dataWorkbench['dateAndTime']['todayLateTimeOffset'];
    }


    /**
     *
     */
    private function eventStartingManager()
    {
        // Organisation de l'event et uniquement lui. Pas ses contents.
        $this->dataWorkbench['eventArray'] = $this->objectStore['EventDataOrganizer']->eventGeneralBuildOrder(
            $this->originalDataStore['medic_event_list']['pdoResult'],
            $this->dataWorkbench['dateAndTime'],
            $this->originalDataStore['doc_office_list']['pdoResult'],
            $this->originalDataStore['medic_event_themes_relation']['pdoResult'],
            $this->originalDataStore['medic_event_spemedic_relation']['pdoResult'],
            $this->originalDataStore['doc_spemedic_relation']['pdoResult'],
            $this->originalDataStore['spe_medic_full_list']['pdoResult'],
            $this->originalDataStore['doc_list']['pdoResult']
        );
    }


    /**
     *
     */
    private function prescManager()
    {
        // Organisation des prescriptions d'ordonnances de prélèvements
        $this->dataWorkbench['prescOrdoLabo'] = $this->objectStore['PrescOrdoLaboOrganizer']->prescOrdoLaboGeneralBuildOrder(
            $this->originalDataStore['presc_labo_list']['pdoResult'],
            $this->originalDataStore['presc_labo_elements']['pdoResult']
        );


        // Organisation des prescriptions d'ordonnances de traitements pharmaceutiques
        $this->dataWorkbench['prescOrdoPharma'] = $this->objectStore['PrescOrdoPharmaOrganizer']->prescOrdoPharmaGeneralBuildOrder(
            $this->originalDataStore['presc_pharma_list']['pdoResult'],
            $this->originalDataStore['treat_pharma_list']['pdoResult'],
            $this->originalDataStore['treat_pharma_role_on_affect']['pdoResult'],
            $this->originalDataStore['medic_affect_list']['pdoResult']
        );


        // Organisation des prescriptions d'ordonnances vaccinales
        $this->dataWorkbench['prescOrdoVax'] = $this->objectStore['PrescOrdoVaxOrganizer']->prescOrdoVaxGeneralBuildOrder(
            $this->originalDataStore['presc_vax_list']['pdoResult'],
            $this->originalDataStore['treat_pharma_list']['pdoResult']
        );
    }


    /**
     *
     */
    private function ordoManager()
    {
        // Récupération des presc et slots d'ordo labo puis regroupement dans les ordonnances d'ordo labo
        $this->dataWorkbench['ordoLabo'] = $this->objectStore['OrdoLaboOrganizer']->ordoLaboGeneralBuildOrder(
            $this->originalDataStore['ordo_labo_list']['pdoResult'],
            $this->dataWorkbench['dateAndTime'],
            $this->dataWorkbench['prescOrdoLabo'],
            $this->originalDataStore['ordo_labo_slots']['pdoResult']
        );

        // Récupération des presc d'ordo labo puis regroupement dans les ordonnances d'ordo pharma
        $this->dataWorkbench['ordoPharma'] = $this->objectStore['OrdoPharmaOrganizer']->ordoPharmaGeneralBuildOrder(
            $this->originalDataStore['ordo_pharma_list']['pdoResult'],
            $this->dataWorkbench['dateAndTime'],
            $this->dataWorkbench['prescOrdoPharma']
        );

        // Récupération des presc et slots d'ordo vax puis regroupement dans les ordonnances d'ordo vax
        $this->dataWorkbench['ordoVax'] = $this->objectStore['OrdoVaxOrganizer']->ordoVaxGeneralBuildOrder(
            $this->originalDataStore['ordo_vax_list']['pdoResult'],
            $this->dataWorkbench['dateAndTime'],
            $this->dataWorkbench['prescOrdoVax'],
            $this->originalDataStore['ordo_vax_slots']['pdoResult']
        );

        // Récupération des presc et slots d'ordo vax puis regroupement dans les ordonnances d'ordo vax
        $this->dataWorkbench['ordoSight'] = $this->objectStore['OrdoSightOrganizer']->ordoSightGeneralBuildOrder(
            $this->originalDataStore['ordo_sight_list']['pdoResult'],
            $this->dataWorkbench['dateAndTime']
        );

        // Rassemblement de toutes les ordonnances dans un seul array et tri par timestamp par ordre croissant
        $this->dataWorkbench['ordoGlobalArray'] = $this->objectStore['OrdoGatherAndSorting']->ordoArrayGeneralBuildOrder(
            $this->dataWorkbench['ordoLabo'],
            $this->dataWorkbench['ordoPharma'],
            $this->dataWorkbench['ordoVax'],
            $this->dataWorkbench['ordoSight']
        );
    }


    /**
     *
     */
    private function diagManager()
    {
        // Ajout des noms de themes pour chaque diagnostic
        $this->dataWorkbench['diagThemes'] = $this->objectStore['DiagThemesOrganizer']->diagThemesGeneralBuildOrder(
            $this->originalDataStore['diag_medic_themes_relation']['pdoResult'],
            $this->originalDataStore['medic_theme_list']['pdoResult']
        );


        $this->dataWorkbench['diagConclusions'] = $this->objectStore['DiagConclusionsOrganizer']->diagConclusionsGeneralBuildOrder(
            $this->originalDataStore['diag_conclusions']['pdoResult'],
            $this->originalDataStore['medic_affect_list']['pdoResult']
        );

        $this->dataWorkbench['diagList'] = $this->objectStore['DiagListOrganizer'] ->diagListArrayGeneralBuildOrder(
            $this->originalDataStore['diag_list']['pdoResult'],
            $this->originalDataStore['diag_check_points']['pdoResult'],
            $this->dataWorkbench['diagThemes'],
            $this->originalDataStore['diag_symptoms']['pdoResult'],
            $this->dataWorkbench['diagConclusions'],
            $this->dataWorkbench['ordoGlobalArray']
        );
    }


    /**
     *
     */
    private function careSessionsManager()
    {
        $this->dataWorkbench['careSessions'] = $this->objectStore['CareSessionsOrganizer']->careSessionsGeneralBuildOrder(
            $this->originalDataStore['care_sessions_list']['pdoResult'],
            $this->originalDataStore['care_session_elements']['pdoResult']
        );
    }


    /**
     *
     */
    private function vaxSessionsManager()
    {
        $this->dataWorkbench['vaxSessions'] = $this->objectStore['VaxSessionsOrganizer']->vaxSessionsGeneralBuildOrder(
            $this->originalDataStore['vax_sessions_list']['pdoResult'],
            $this->originalDataStore['vax_sessions_side_effects']['pdoResult']
        );
    }


    /**
     *
     */
    private function eventEndingManager()
    {
        $this->dataWorkbench['eventFinalArray'] = $this->objectStore['EventFinalContentMerger']->eventContentMerger(
            $this->dataWorkbench['eventArray'],
            $this->dataWorkbench['dateAndTime']['todayEarlyTimestamp'],
            $this->dataWorkbench['diagList'],
            $this->dataWorkbench['careSessions'],
            $this->dataWorkbench['vaxSessions']
        );
    }
}
