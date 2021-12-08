<?php

namespace HealthKerd\View\home;

class HomePageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array|null $eventsData = array();
    private string $builtContentHTML = '';
    private string|null $futureEventsHTML = '';

    private object $eventsBuilder;

    /** */
    public function __construct()
    {
        parent::__construct();

        $this->pageSettingsList = array(
            "pageTitle" => "Accueil"
        );
    }

    public function __destruct()
    {
    }


    public function dataReceiver(array $medicEvtProcessedDataStore)
    {
        $this->eventsData = $medicEvtProcessedDataStore;
        $this->eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
        $this->futureEventsHTML = $this->eventsBuilder->eventBuildOrder($this->eventsData['futureEvents']);
        $this->buildOrder();
    }




    private function buildOrder()
    {
        $this->builtContentHTML .= '<h3>&Eacute;vénements médicaux à venir: ' . sizeof($this->eventsData['futureEvents']) . '</h3>';
        $this->builtContentHTML .= $this->futureEventsHTML;


        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }
}
