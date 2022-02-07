<?php

namespace HealthKerd\Controller\medic\eventCat;

/** Controller GET des catégories d'events
*/
class EventCatGetController extends EventCatCommonController
{
    private array $cleanedUpGet;

    private object $medicEventIdFinder; // Modéle spéclialisé dans la recherche d'ID d'events médicaux
    private object $medicEventDataGatherer;
    private object $medicEventArrayBuildOrder;
    private object $catView;


    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Paris');
        $this->medicEventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $this->medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $this->medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicEventArrayBuildOrder();
    }

    public function __destruct()
    {
    }

    /** recoit GET['action'] et lance la suite
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     * @return void
     */
    public function actionReceiver(array $cleanedUpGet)
    {
        $this->cleanedUpGet = $cleanedUpGet;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'dispAllEventCats': // affichage de toutes les catégories d'events
                    $eventCatsList = $this->eventCatModel->gatherAllEventsCats();

                    $this->catView = new \HealthKerd\View\medic\eventCats\eventCatsList\EventCatsListPageBuilder();
                    $this->catView->dataReceiver($eventCatsList);
                    break;

                case 'dispAllEventsRegrdOneCat': // affichage de tous les events ayant une catégorie particulière
                    $this->dispAllEventsRegardingOneCat();
                    break;

                default: // si GET['action'] ne correspond à aucun cas de figure, on repart vers la liste des catégories d'events
                    echo "<script> window.location.replace('index.php?controller=medic&subCtrlr=eventCat&action=dispAllEventCats') </script>";
            }
        } else {
            // si GET['action'] n'est pas défini, on repart vers la liste des catégories d'events
            echo "<script> window.location.replace('index.php?controller=medic&subCtrlr=eventCat&action=dispAllEventCats') </script>";
        }
    }


    /** Affichage de tous les events ayant une catégorie particulière
    */
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
