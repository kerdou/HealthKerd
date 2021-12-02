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
                    $docController = new \HealthKerd\Controller\medic\allEvents\AllEventsGetController();
                    $docController->displayAllEvents();
                    break;
                case 'doc':
                    $docController = new \HealthKerd\Controller\medic\doc\DocGetController();
                    $docController->actionReceiver($cleanedUpGet);
                    break;
                case 'docPost':
                        // TO DO
                    break;
                default:
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            echo "<script>window.location = 'index.php';</script>";
        }
    }
}
