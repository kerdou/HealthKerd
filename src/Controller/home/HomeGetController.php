<?php

namespace HealthKerd\Controller\home;

/** Controleur Get de la page d'accueil */
class HomeGetController
{
    private object $homeModel; // Récupére les données des 3 derniers clients et des 3 derniers prospects
    private object $medicEventIdFinder; // Modéle spéclialisé dans la recherche d'ID d'events médicaux
    private object $medicEventDataGatherer; // Récupération de toutes les données de tous les events médicaux concernés
    private object $medicEventArrayBuildOrder; // Remodelage des données des events pour faciliter la construction du HTML dans le View
    private object $homeView; // Affichage des tableaux de la section accueil

    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');
        $this->homeModel = new \HealthKerd\Model\home\HomeModel();
        $this->medicEventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $this->medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $this->medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicEventArrayBuildOrder();
        $this->homeView = new \HealthKerd\View\home\HomePageBuilder();
    }

    public function __destruct()
    {
    }

    /** Affichage de la page d'accueil */
    public function displayHomePage()
    {
        $medicEventsIdResult = $this->medicEventIdFinder->comingEventsIds();
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
            //print_r($medicEvtOriginalDataStore);
            //print_r($medicEvtProcessedDataStore);
        //echo '</pre>';

        //var_dump($medicEvtProcessedDataStore);

        $this->homeView->dataReceiver($medicEvtProcessedDataStore);
    }
}
