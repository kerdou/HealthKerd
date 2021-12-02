<?php

namespace HealthKerd\Controller\medic\allEvents;

/** Controleur de la section 'accueil' */
class AllEventsGetController
{
    private object $medicEventIdFinder; // Modéle spéclialisé dans la recherche d'ID d'events médicaux
    private object $medicEventDataGatherer;
    private object $medicEventArrayBuildOrder;
    private object $allEventsView; // Affichage des tableaux de la section accueil

    public function __construct()
    {
        //date_default_timezone_set('Europe/Paris');
        $this->medicEventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $this->medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $this->medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicArrayBuildOrder();
        $this->allEventsView = new \HealthKerd\View\medic\allEvents\AllEventsPageBuilder();
    }

    public function __destruct()
    {
    }

    public function displayAllEvents()
    {
        $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsByUserId();
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

        $this->allEventsView->dataReceiver($medicEvtProcessedDataStore);
    }
}
