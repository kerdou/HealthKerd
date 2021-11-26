<?php

namespace HealthKerd\View\home;

class HomePageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array|null $eventsData = array();
    private string|null $futureEventsHTML = '';

    private object $eventsBuilder;

    public function __construct()
    {
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
        $this->futureEventsHTML = $this->eventsBuilder->eventBuildOrder($this->eventsData['pastEvents']);
        $this->buildOrder();
    }




    private function buildOrder()
    {
        $this->pageContent = file_get_contents(__DIR__ . '../../../../public/html/globalLayout/head.html');
        $this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/header.html');
        $this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/myContentOpener.html');
        $this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/sidebar.html');
        $this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/offCanvas-sidebar.html');
        $this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/mainOpener.html');

        $this->pageContent .= "<h3>&Eacute;vénements médicaux à venir</h3>";
        //$this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/test/modalButtonTest.html');
        $this->pageContent .= $this->futureEventsHTML;

        $this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/myContentRowAndMainFinisher.html');
        $this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/modalStoreOpener.html');
        $this->pageContent .= file_get_contents(__DIR__ . '../../../../templates/modals/speMedicModals.html');
        $this->pageContent .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/pageBottom.html');

        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }
}
