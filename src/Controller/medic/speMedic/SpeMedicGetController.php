<?php

namespace HealthKerd\Controller\medic\speMedic;

/** Controleur GET des spécialités médicales */
class SpeMedicGetController
{
    private array $cleanedUpGet;

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
                case 'dispAllSpeMedics': // affichage de toutes les spécialités médicales
                    $this->displayAllSpeMedics();
                    break;

                case 'dispAllEventsRegrdOneSpe': // affichage de tous les events vis à vis d'une spé en particulier
                    $this->dispAllEventsRegardingOneSpeMedic();
                    break;

                default: // si le Get['action'] ne correspond à rien, retour à l'affichage de la liste des spé médicales
                    $this->displayAllSpeMedics();
            }
        } else {
            // si le Get['action'] n'est pas défini, retour à l'affichage de la liste des spé médicales
            $this->displayAllSpeMedics();
        }
    }

    /** Affichage de toutes les spécialités médicales
    */
    private function displayAllSpeMedics(): void
    {
        $speMedicModel = new \HealthKerd\Model\modelInit\medic\speMedic\SpeMedicSelectModel();
        $speMedicList = $speMedicModel->selectSpeMedicUsedByUser();

        $speView = new \HealthKerd\View\medic\speMedic\speMedicList\SpeMedicListPageBuilder();
        $speView->buildOrder($speMedicList);
    }

    /** Affichage de tous les events vis à vis d'une spé en particulier
     */
    private function dispAllEventsRegardingOneSpeMedic(): void
    {
        $eventFinderAndGathererController = new \HealthKerd\Controller\medic\eventsFinderAndGatherer\EventsFinderAndGathererGetController();
        $processedData = $eventFinderAndGathererController->actionReceiver('eventsIdsFromSpeMedicId', $this->cleanedUpGet);

        $speView = new \HealthKerd\View\medic\speMedic\AllEventsRegrdOneSpe\AllEventsRegardOneSpePageBuilder();
        $speView->buildOrder($processedData);
    }
}
