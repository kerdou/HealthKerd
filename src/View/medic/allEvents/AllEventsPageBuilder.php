<?php

namespace HealthKerd\View\medic\allEvents;

class AllEventsPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array|null $eventsData = array();
    private string $builtContentHTML = '';
    private string|null $pastEventsHTML = '';
    private string|null $futureEventsHTML = '';

    private object $eventsBuilder;

    /** */
    public function __construct()
    {
        parent::__construct();
        /*
        $this->pageSettingsList = array(
            "pageTitle" => "Page de connexion"
        );
        */
    }


    public function __destruct()
    {
    }


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




    /** */
    private function buildOrder()
    {
        if (sizeof($this->eventsData['futureEvents']) > 0) {
            $this->builtContentHTML .= "<h3>&Eacute;vénements médicaux à venir</h3>";
            $this->builtContentHTML .= $this->futureEventsHTML;
        }

        if (sizeof($this->eventsData['pastEvents']) > 0) {
            $this->builtContentHTML .= "<h3>&Eacute;vénements médicaux passés</h3>";
            $this->builtContentHTML .= $this->pastEventsHTML;
        }

        if ((sizeof($this->eventsData['futureEvents']) == 0)  &&  (sizeof($this->eventsData['pastEvents']) == 0)) {
            $this->builtContentHTML .= "<h3>Aucun événement médical lié à ce professionnel de santé trouvé</h3>";
        }

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }
}