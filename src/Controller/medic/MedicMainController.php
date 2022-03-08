<?php

namespace HealthKerd\Controller\medic;

/** Contrôleur de la sous-catégorie médicale
*/
class MedicMainController
{
    public function __destruct()
    {
    }

    /** Récupére $_GET['action'] et POST nettoyés, puis lance les controlleurs voulus pour le domaine médical
     * @param array $cleanedUpGet       Contenu du GET nettoyé
     * @param array $cleanedUpPost      Contenu du POST nettoyé
    */
    public function subContReceiver(array $cleanedUpGet, array $cleanedUpPost): void
    {
        if (isset($cleanedUpGet['subCtrlr'])) {
            switch ($cleanedUpGet['subCtrlr']) {
                case 'allEventsDisp': // affichage de tous les events médicaux du user
                    $allEventsController = new \HealthKerd\Controller\medic\allEvents\AllEventsGetController();
                    $allEventsController->displayAllEvents();
                    break;

                case 'doc': // affichage des pages concernant les docteurs
                    $docController = new \HealthKerd\Controller\medic\doc\DocGetController();
                    $docController->actionReceiver($cleanedUpGet);
                    break;

                case 'docPost': // traitement des données de formulaires concernant les docteurs
                    $docPostController = new \HealthKerd\Controller\medic\doc\DocPostController();
                    $docPostController->actionReceiver($cleanedUpGet, $cleanedUpPost);
                    break;

                case 'docOffice': // affichage des pages concernant les cabinets médicaux
                    $eventDocOfficeController = new \HealthKerd\Controller\medic\docOffice\DocOfficeGetController();
                    $eventDocOfficeController->actionReceiver($cleanedUpGet);
                    break;

                case 'medicTheme': // affichage des pages concernant les thèmes médicaux
                    $medicThemeController = new \HealthKerd\Controller\medic\medicTheme\MedicThemeGetController();
                    $medicThemeController->actionReceiver($cleanedUpGet);
                    break;

                case 'eventCat': // affichage des pages concernant les catégories d'events
                    $eventCatController = new \HealthKerd\Controller\medic\eventCat\EventCatGetController();
                    $eventCatController->actionReceiver($cleanedUpGet);
                    break;

                case 'speMedic': // affichage des pages concernant les spécialités médicales
                    $speMedicController = new \HealthKerd\Controller\medic\speMedic\SpeMedicGetController();
                    $speMedicController->actionReceiver($cleanedUpGet);
                    break;

                default: // si $_GET['action'] ne correspond à aucun cas de figure, on repart vers le controlleur maître
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            // si $_GET['action'] n'est pas défini, on repart vers le controlleur maître
            echo "<script>window.location = 'index.php';</script>";
        }
    }
}
