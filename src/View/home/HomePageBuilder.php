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
        $this->eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
        /*
        $this->pageSettingsList = array(
            "pageTitle" => "Page de connexion"
        );
        */
    }

    public function dataReceiver(array $medicEvtProcessedDataStore)
    {
        //echo '<pre>';
        //print_r($medicEvtProcessedDataStore);
        //echo '</pre>';

        $this->eventsData = $medicEvtProcessedDataStore;
        $this->futureEventsHTML = $this->eventsBuilder->eventBuildOrder($this->eventsData['futureEvents']);
        $this->buildOrder();
    }




    private function buildOrder()
    {
        //$this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/test/modalButtonTest.html');
        $this->builtContentHTML .= "<h3>&Eacute;vénements médicaux à venir</h3>";
        $this->builtContentHTML .= $this->futureEventsHTML;


        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }
}
