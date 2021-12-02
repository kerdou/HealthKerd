<?php

namespace HealthKerd\View\medic\doc\eventsWithOneDoc;

class EventsWithOneDocPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array|null $eventsData = array();
    private string $builtContentHTML = '';
    private string|null $pastEventsHTML = '';
    private string|null $futureEventsHTML = '';


    public function __construct()
    {
        parent::__construct();
        $this->eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
        /*
        $this->pageSettingsList = array(
            "pageTitle" => "Page de connexion"
        );
        */
    }

    /** PLOP */
    public function dataReceiver(array $medicEvtProcessedDataStore)
    {
        $this->eventsData = $medicEvtProcessedDataStore;
        $this->pastEventsHTML = $this->eventsBuilder->eventBuildOrder($this->eventsData['pastEvents']);
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
