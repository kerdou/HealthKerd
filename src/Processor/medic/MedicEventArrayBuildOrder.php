<?php

namespace HealthKerd\Processor\medic;

/** Construction de l'array réunissant toutes les données recombinées en provenance des contrôleurs
 * Cette étape facilite la construction du HTML dans le View car tout sera à la bonne place et facilement accessible
 */
class MedicEventArrayBuildOrder
{
    private array $originalDataStore = array(); // données venant du medicEventDataGatherer
    private array $dataWorkbench = array(); // données stockées dans un état de traitement intermédiaire
    private array $objectStore = array(); // stockage de toutes les instances de traitement de données
    private array $processedDataArray = array(); // résultat final

    public function __destruct()
    {
    }

    /** Recoit les données des events depuis les controleurs puis les réorganise avant de les envoyer pour affichage
     * @param array $receivedDataStore  Données complètes des events à l'état brut
     * @return array                    Données modifiées prêtes à être envoyées pour affichage
     */
    public function eventDataReceiver(array $receivedDataStore): array
    {
        $this->originalDataStore = $receivedDataStore;

        if ($this->originalDataStore != array()) {
            // spécialités médicales des docteurs, et docteurs
            $this->docSpeMedicOrganizer();
            $this->docListOrganizer();

            // gestion de la couche de base des events
            $this->eventBasicsManager();

            // gestion des ordonnances et des diagnostics
            $this->ordoManager();
            $this->diagManager();

            // gestion des sessions de soin et de vaccination
            $this->careSessionsManager();
            $this->vaxSessionsManager();

            // merge final de toutes les données
            $this->eventsFinalDataMerge();

            return $this->processedDataArray;
        } else {
            return array(
                'pastEvents' => array(),
                'futureEvents' => array()
            );
        }
    }

    /** Fusion des arrays de spécialités médicales des docs pour avoir une liste complête sans doublons
    */
    private function docSpeMedicOrganizer(): void
    {
        $this->objectStore['SpeMedicForDocsOrganizer'] = new \HealthKerd\Processor\medic\speMedic\DocSpeMedicOrganizer();
        $this->dataWorkbench['spe_medic_for_docs'] = $this->objectStore['SpeMedicForDocsOrganizer']->docSpeMedicOrganizer(
            $this->originalDataStore['spe_medic_for_attended_docs_on_events']['pdoResult'],
            $this->originalDataStore['spe_medic_for_replaced_docs_on_events']['pdoResult'],
            $this->originalDataStore['spe_medic_for_attended_labo_ordo_docs_on_events']['pdoResult'],
            $this->originalDataStore['spe_medic_for_replaced_labo_ordo_docs_on_events']['pdoResult']
        );
    }

    /** Renvoie un array où doc est unique et a recu les traitements suivants
     * * Réorganisation des données pour faciliter les étapes suivantes
     * * Création de la phrase réunissant titre + prénom + nom
     * * Ajout des spécialités médicales
     */
    private function docListOrganizer(): void
    {
        $this->objectStore['DocOrganizer'] = new \HealthKerd\Processor\medic\doc\DocListOrganizerForEvent();
        $this->dataWorkbench['docList'] = array(); // Données liées aux docteurs et à leur spécialités médicales
        $this->dataWorkbench['docList'] = $this->objectStore['DocOrganizer']->docListOrganizer(
            $this->originalDataStore['doc_list_AttendedDoc']['pdoResult'],
            $this->originalDataStore['doc_list_ReplacedDoc']['pdoResult'],
            $this->originalDataStore['doc_list_LaboOrdoDoc']['pdoResult'],
            $this->originalDataStore['doc_list_LaboOrdoReplacedDoc']['pdoResult'],
            $this->dataWorkbench['spe_medic_for_docs']
        );
    }

    /** Organisation de l'event et uniquement lui; pas de ses contenus.
    */
    private function eventBasicsManager(): void
    {
        $this->objectStore['EventDataOrganizer'] = new \HealthKerd\Processor\medic\event\EventDataOrganizer();
        $this->dataWorkbench['eventArray'] = array(); // liste des events
        $this->dataWorkbench['eventArray'] = $this->objectStore['EventDataOrganizer']->eventGeneralBuildOrder(
            $this->originalDataStore['medic_event_list']['pdoResult'],
            $this->originalDataStore['medic_event_themes_relation']['pdoResult'],
            $this->originalDataStore['medic_event_spemedic_relation']['pdoResult'],
            $this->dataWorkbench['docList'],
        );
    }

    /** Gestion des ordonnances:
     * * Ordonnances de prélèvement en laboratoire médical
     * * Ordonnances pharmaceutiques
     * * Ordonnances vaccinnales
     * * Ordonnances optiques
     * * Regroupement des ordonnances
     */
    private function ordoManager(): void
    {
        // Récupération des presc et slots d'ordo labo puis regroupement dans les ordonnances d'ordo labo
        $this->objectStore['OrdoLaboOrganizer'] = new \HealthKerd\Processor\medic\event\diag\ordo\OrdoLaboOrganizer();
        $this->dataWorkbench['ordoLabo'] = array();
        $this->dataWorkbench['ordoLabo'] = $this->objectStore['OrdoLaboOrganizer']->ordoLaboGeneralBuildOrder(
            $this->originalDataStore['ordo_labo_list']['pdoResult'],
            $this->originalDataStore['presc_labo_list']['pdoResult'],
            $this->originalDataStore['presc_labo_elements']['pdoResult'],
            $this->originalDataStore['ordo_labo_slots_for_diags']['pdoResult']
        );

        // Récupération des presc d'ordo labo puis regroupement dans les ordonnances d'ordo pharma
        $this->objectStore['OrdoPharmaOrganizer'] = new \HealthKerd\Processor\medic\event\diag\ordo\OrdoPharmaOrganizer();
        $this->dataWorkbench['ordoPharma'] = array(); // Ordonnances pharmaceutiques
        $this->dataWorkbench['ordoPharma'] = $this->objectStore['OrdoPharmaOrganizer']->ordoPharmaGeneralBuildOrder(
            $this->originalDataStore['ordo_pharma_list']['pdoResult'],
            $this->originalDataStore['presc_pharma_list']['pdoResult']
        );

        // Récupération des ordonnances optiques
        $this->objectStore['OrdoSightOrganizer'] = new \HealthKerd\Processor\medic\event\diag\ordo\OrdoSightOrganizer();
        $this->dataWorkbench['ordoSight'] = array(); // Ordonnances optiques
        $this->dataWorkbench['ordoSight'] = $this->objectStore['OrdoSightOrganizer']->ordoSightGeneralBuildOrder(
            $this->originalDataStore['ordo_sight_list']['pdoResult']
        );

        // Récupération des presc et slots d'ordo vax puis regroupement dans les ordonnances d'ordo vax
        $this->objectStore['OrdoVaxOrganizer'] = new \HealthKerd\Processor\medic\event\diag\ordo\OrdoVaxOrganizer();
        $this->dataWorkbench['ordoVax'] = array(); // Ordonnances vaccinnales
        $this->dataWorkbench['ordoVax'] = $this->objectStore['OrdoVaxOrganizer']->ordoVaxGeneralBuildOrder(
            $this->originalDataStore['ordo_vax_list']['pdoResult'],
            $this->originalDataStore['presc_vax_list']['pdoResult'],
            $this->originalDataStore['ordo_vax_slots_for_diags']['pdoResult']
        );

        // Rassemblement de toutes les ordonnances dans un seul array et tri par timestamp par ordre croissant
        $this->objectStore['OrdoGatherAndSorting'] = new \HealthKerd\Processor\medic\event\diag\ordo\OrdoGatherAndSorting();
        $this->dataWorkbench['ordoGlobalArray'] = array(); // Regroupement des ordonnances
        $this->dataWorkbench['ordoGlobalArray'] = $this->objectStore['OrdoGatherAndSorting']->ordoArrayGeneralBuildOrder(
            $this->dataWorkbench['ordoLabo'],
            $this->dataWorkbench['ordoPharma'],
            $this->dataWorkbench['ordoVax'],
            $this->dataWorkbench['ordoSight']
        );
    }

    /** Gestion des diagnostics:
     * * Thèmes de diagnostics
     * * Conclusions de diagnostics
     * * Liste des diagnostics
     */
    private function diagManager(): void
    {
        // Création des diagnostics
        $this->objectStore['DiagListOrganizer'] = new \HealthKerd\Processor\medic\event\diag\DiagListOrganizer();
        $this->dataWorkbench['diagList'] = array(); // Liste des diagnostics
        $this->dataWorkbench['diagList'] = $this->objectStore['DiagListOrganizer']->diagListArrayGeneralBuildOrder(
            $this->originalDataStore['diag_list']['pdoResult'],
            $this->originalDataStore['diag_symptoms']['pdoResult'],
            $this->originalDataStore['diag_check_points']['pdoResult'],
            $this->originalDataStore['diag_conclusions']['pdoResult'],
            $this->dataWorkbench['ordoGlobalArray']
        );
    }

    /** Gestion des sessions de soins
     */
    private function careSessionsManager(): void
    {
        $this->objectStore['CareSessionsOrganizer'] = new \HealthKerd\Processor\medic\event\care\CareSessionsOrganizer();
        $this->dataWorkbench['careSessions'] = array(); // Sessions de soin
        $this->dataWorkbench['careSessions'] = $this->objectStore['CareSessionsOrganizer']->careSessionsGeneralBuildOrder(
            $this->originalDataStore['care_sessions_list']['pdoResult'],
            $this->originalDataStore['care_session_elements']['pdoResult']
        );
    }

    /** Gestion des sessions de vaccination
     */
    private function vaxSessionsManager(): void
    {
        $this->objectStore['VaxSessionsOrganizer'] = new \HealthKerd\Processor\medic\event\vax\VaxSessionsOrganizer();
        $this->dataWorkbench['vaxSessions'] = array(); // Sessions de vaccination
        $this->dataWorkbench['vaxSessions'] = $this->objectStore['VaxSessionsOrganizer']->vaxSessionsGeneralBuildOrder(
            $this->originalDataStore['vax_sessions_list']['pdoResult'],
            $this->originalDataStore['vax_sessions_side_effects']['pdoResult'],
            $this->originalDataStore['ordo_vax_slots_for_sessions']['pdoResult']
        );
    }

    /** Finalisation de l'array des events et renvoie dans $processedDataArray
     */
    private function eventsFinalDataMerge(): void
    {
        $this->objectStore['EventFinalContentMerger'] = new \HealthKerd\Processor\medic\event\EventFinalContentMerger();
        $this->processedDataArray = $this->objectStore['EventFinalContentMerger']->eventContentMerger(
            $this->dataWorkbench['eventArray'],
            $this->dataWorkbench['diagList'],
            $this->dataWorkbench['careSessions'],
            $this->dataWorkbench['vaxSessions']
        );
    }
}
