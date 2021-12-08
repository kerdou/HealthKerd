<?php

namespace HealthKerd\Controller\medic\eventCat;

/** Controleur de la section 'accueil' */
class EventCatGetController extends EventCatCommonController
{
    private array $cleanedUpGet;

    private object $medicEventIdFinder; // Modéle spéclialisé dans la recherche d'ID d'events médicaux
    private object $medicEventDataGatherer;
    private object $medicEventArrayBuildOrder;
    private object $catView;


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
                case 'dispAllEventCats':
                    $eventCatsList = $this->eventCatModel->gatherAllEventsCats();

                    $this->catView = new \HealthKerd\View\medic\eventCats\eventCatsList\EventCatsListPageBuilder();
                    $this->catView->dataReceiver($eventCatsList);
                    break;
                case 'dispAllEventsRegrdOneCat':
                    $this->dispAllEventsRegardingOneCat();
                    break;
                default:
                    //$this->displayAllDocList();
            }
        } else {
            //$this->displayAllDocList();
        }
    }

    /** */
    public function dispAllEventsRegardingOneCat()
    {
        $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsbyCatId($this->cleanedUpGet['medicEventCatID']);
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

        $this->catView = new \HealthKerd\View\medic\eventCats\allEventsRegardingOneCat\AllEventsRegardingOneCatPageBuilder();
        $this->catView->dataReceiver($medicEvtProcessedDataStore);
    }
}
