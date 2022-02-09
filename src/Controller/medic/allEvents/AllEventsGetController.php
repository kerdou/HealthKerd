<?php

namespace HealthKerd\Controller\medic\allEvents;

/** Controleur de l'affichage de tous les events du user */
class AllEventsGetController
{
    private object $medicEventIdFinder; // Modéle spécialisé dans la recherche d'ID d'events médicaux
    private object $medicEventDataGatherer; // Récupération de toutes les données des events
    private object $medicEventArrayBuildOrder; // Réassemblage de toutes les données des events
    private object $allEventsView; // Affichage des tableaux de la section accueil

    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');
        $this->medicEventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $this->medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $this->medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicEventArrayBuildOrder();
        $this->allEventsView = new \HealthKerd\View\medic\allEvents\AllEventsPageBuilder();
    }

    public function __destruct()
    {
    }

    /** Affichage de tous les events d'un user:
     * * D'abord on récupére les eventsID avec la classe medicEventIdFinder par rapport à l'ID du user
     * * Puis envoie ces ID à la classe medicEventDataGatherer qui va récupérer toutes les données necessaires
     * * Puis le résultat est envoyé à la classe medicEventArrayBuildOrder qui va rassembler les données event par event
     * * Puis la classe allEventsView va afficher le tout
     */
    public function displayAllEvents()
    {
        $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsByUserId(); // model
        $medicEventsIdList = array();

        // conversion des ID d'event en integer
        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        $medicEvtOriginalDataStore = $this->medicEventDataGatherer->eventIdReceiver($medicEventsIdList); // model
        $medicEvtProcessedDataStore = $this->medicEventArrayBuildOrder->eventDataReceiver($medicEvtOriginalDataStore); // processor

        // vidage de $medicEvtOriginalDataStore
        unset($medicEvtOriginalDataStore);
        $medicEvtOriginalDataStore = array();

        $this->allEventsView->dataReceiver($medicEvtProcessedDataStore); // view
    }
}
