<?php

namespace HealthKerd\View\medic\allEvents;

/** Construction puis affichage de la page contenant tous les events (passés et futurs) d'un user
 */
class AllEventsPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $eventsData = array();
    private string $builtContentHTML = '';
    private string $pastEventsHTML = '';
    private string $futureEventsHTML = '';

    private object $eventsBuilder;

    public function __construct()
    {
        parent::__construct();

        $this->pageSettingsList = array(
            "pageTitle" => "Liste de tous les événements"
        );
    }

    public function __destruct()
    {
    }

    /** Recoit les données de tous les rendez-vous puis lance la construction du HTML de ce contenu
     * * On construit d'abord les events passés
     * * On construit ensuite les events futurs
     * @param array $medicEvtProcessedDataStore     Donnée réassamblée et réagencée dans le Processor pour faciliter la création du HTML
     */
    public function dataReceiver(array $medicEvtProcessedDataStore)
    {
        $this->eventsData = $medicEvtProcessedDataStore;

        $this->eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
        $this->pastEventsHTML = $this->eventsBuilder->eventBuildOrder($this->eventsData['pastEvents']);

        // eventBuilder est détruit à la fin de son execution donc on le recrée pour les futureEvents
        $this->eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
        $this->futureEventsHTML = $this->eventsBuilder->eventBuildOrder($this->eventsData['futureEvents']);
        $this->buildOrder();
    }

    /** Adaptation de la page suivant la présence ou l'absence des events passés et futurs
     * Puis configuration de la page et affichage du contenu
     */
    private function buildOrder()
    {
        if (sizeof($this->eventsData['futureEvents']) > 0) {
            $this->builtContentHTML .= '<h3>&Eacute;vénements médicaux à venir: ' . sizeof($this->eventsData['futureEvents']) . '</h3>';
            $this->builtContentHTML .= $this->futureEventsHTML;
        }

        if (sizeof($this->eventsData['pastEvents']) > 0) {
            $this->builtContentHTML .= '<h3>&Eacute;vénements médicaux passés: ' . sizeof($this->eventsData['pastEvents']) . '</h3>';
            $this->builtContentHTML .= $this->pastEventsHTML;
        }

        if ((sizeof($this->eventsData['futureEvents']) == 0)  &&  (sizeof($this->eventsData['pastEvents']) == 0)) {
            $this->builtContentHTML .= "<h3>Aucun événement médical lié à ce professionnel de santé trouvé</h3>";
        }

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }
}
