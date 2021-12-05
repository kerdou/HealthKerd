<?php

namespace HealthKerd\Controller\medic;

/** Controleur de la section 'accueil' */
class MedicMainController
{

    public function __destruct()
    {
    }

    /** Récupére [$_GET['action']] et lance l'affichage de la page voulue */
    public function subContReceiver(array $cleanedUpGet, array $cleanedUpPost)
    {
        if (isset($cleanedUpGet['subCtrlr'])) {
            switch ($cleanedUpGet['subCtrlr']) {
                case 'allEventsDisp':
                    $allEventsController = new \HealthKerd\Controller\medic\allEvents\AllEventsGetController();
                    $allEventsController->displayAllEvents();
                    break;

                case 'doc':
                    $docController = new \HealthKerd\Controller\medic\doc\DocGetController();
                    $docController->actionReceiver($cleanedUpGet);
                    break;

                case 'docPost':
                        // TO DO
                    break;

                case 'docOffice':
                    $eventDocOfficeController = new \HealthKerd\Controller\medic\docOffice\DocOfficeGetController();
                    $eventDocOfficeController->actionReceiver($cleanedUpGet);
                    break;

                case 'medicTheme':
                    $medicThemeController = new \HealthKerd\Controller\medic\medicTheme\MedicThemeGetController();
                    $medicThemeController->actionReceiver($cleanedUpGet);
                    break;

                case 'eventCat':
                    $eventCatController = new \HealthKerd\Controller\medic\eventCat\EventCatGetController();
                    $eventCatController->actionReceiver($cleanedUpGet);
                    break;

                case 'speMedic':
                    $speMedicController = new \HealthKerd\Controller\medic\speMedic\SpeMedicGetController();
                    $speMedicController->actionReceiver($cleanedUpGet);
                    break;

                default:
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            echo "<script>window.location = 'index.php';</script>";
        }
    }
}
