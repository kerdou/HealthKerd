<?php

namespace HealthKerd\Controller\medic\eventCat;

/** Controller GET des catégories d'events
*/
class EventCatGetController
{
    private array $cleanedUpGet;

    private object $catView;

    public function __destruct()
    {
    }

    /** recoit GET['action'] et lance la suite
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     */
    public function actionReceiver(array $cleanedUpGet): void
    {
        $this->cleanedUpGet = $cleanedUpGet;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'dispAllEventCats': // affichage de toutes les catégories d'events
                    $eventCatSelectModel = new \HealthKerd\Model\modelInit\medic\eventCat\EventCatSelectModel();
                    $eventCatsList = $eventCatSelectModel->gatherAllEventsCats();

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
        $this->eventFinderAndGathererController = new \HealthKerd\Controller\medic\eventsFinderAndGatherer\EventsFinderAndGathererGetController();
        $processedData = $this->eventFinderAndGathererController->actionReceiver('eventsIdsbyCatId', $this->cleanedUpGet);

        $this->catView = new \HealthKerd\View\medic\eventCats\allEventsRegardingOneCat\AllEventsRegardingOneCatPageBuilder();
        $this->catView->dataReceiver($processedData);
    }
}
