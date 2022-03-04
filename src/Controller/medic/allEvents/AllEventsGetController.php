<?php

namespace HealthKerd\Controller\medic\allEvents;

/** Controleur de l'affichage de tous les events du user */
class AllEventsGetController
{
    private object $eventFinderAndGathererController;
    private object $allEventsView; // Affichage des tableaux de la section accueil

    public function __construct()
    {
        $this->eventFinderAndGathererController = new \HealthKerd\Controller\medic\eventsFinderAndGatherer\EventsFinderAndGathererGetController();
        $this->allEventsView = new \HealthKerd\View\medic\allEvents\AllEventsPageBuilder();
    }

    public function __destruct()
    {
    }

    /** Affichage de tous les events d'un user
     */
    public function displayAllEvents(): void
    {
        $processedData = $this->eventFinderAndGathererController->actionReceiver('eventsIdsByUserId');
        $this->allEventsView->buildOrder($processedData); // view
    }
}
