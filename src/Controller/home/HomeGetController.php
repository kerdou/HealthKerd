<?php

namespace HealthKerd\Controller\home;

/** Controleur Get de la page d'accueil */
class HomeGetController
{
    private object $eventFinderAndGathererController;
    private object $homeView; // Affichage des tableaux de la section accueil

    public function __destruct()
    {
    }

    /** Affichage de la page d'accueil:
    */
    public function displayHomePage(): void
    {
        $this->eventFinderAndGathererController = new \HealthKerd\Controller\medic\eventsFinderAndGatherer\EventsFinderAndGathererGetController();
        $processedData = $this->eventFinderAndGathererController->actionReceiver('comingEventsIds');

        $this->homeView = new \HealthKerd\View\home\HomePageBuilder();
        $this->homeView->dataReceiver($processedData);
    }
}
