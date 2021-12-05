<?php

namespace HealthKerd\Controller\medic\docOffice;

/** Controleur de la section 'accueil' */
class DocOfficeGetController extends DocOfficeCommonController
{
    private array $cleanedUpGet;
    private array $docOfficeList = array();
    private array $speMedicList = array();


    private object $medicEventIdFinder; // Modéle spéclialisé dans la recherche d'ID d'events médicaux
    private object $medicEventDataGatherer;
    private object $medicEventArrayBuildOrder;
    private object $docOfficeView;

    /** */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Paris');
        $this->medicEventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $this->medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $this->medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicArrayBuildOrder();
    }

    public function __destruct()
    {
    }

    /** */
    public function actionReceiver(array $cleanedUpGet)
    {
        $this->cleanedUpGet = $cleanedUpGet;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'allDocOfficesListDisp':
                    $this->displayAllDocOfficesList();
                    break;
                case 'dispEventsWithOneDocOffice':
                    $this->displayEventsWithOneDocOffice();
                    break;
                default:
                    $this->displayAllDocOfficesList();
            }
        } else {
            $this->displayAllDocOfficesList();
        }
    }


    /** */
    private function displayAllDocOfficesList()
    {
        $this->docOfficeList = $this->docOfficeModel->gatherAllDocOffices();

        $this->docOfficeView = new \HealthKerd\View\medic\docOffice\docOfficeList\DocOfficeListPageBuilder();
        $this->docOfficeView->dataReceiver($this->docOfficeList);
    }


    /** */
    private function displayEventsWithOneDocOffice()
    {
        $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsByDocOfficeId($this->cleanedUpGet['docOfficeID']);
        $medicEventsIdList = array();

        // conversion des ID d'event en integer
        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        $medicEvtOriginalDataStore = $this->medicEventDataGatherer->eventIdReceiver($medicEventsIdList);
        $medicEvtProcessedDataStore = $this->medicEventArrayBuildOrder->eventDataReceiver($medicEvtOriginalDataStore);

        // vidage de $medicEvtOriginalDataStore
        unset($medicEvtOriginalDataStore);
        $medicEvtOriginalDataStore = array();

        //echo '<pre>';
        //print_r($medicEvtProcessedDataStore);
        //var_dump($medicEvtProcessedDataStore);
        //echo '</pre>';

        $this->docOfficeView = new \HealthKerd\View\medic\docOffice\allEventsRegrdOneDocOffice\AllEventsRegrdOneDocOfficePageBuilder();
        $this->docOfficeView->dataReceiver($medicEvtProcessedDataStore);
    }
}
