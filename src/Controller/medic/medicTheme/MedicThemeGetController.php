<?php

namespace HealthKerd\Controller\medic\medicTheme;

/** Contrôleur GET des thèmes médicaux
*/
class MedicThemeGetController
{
    private array $cleanedUpGet;

    public function __destruct()
    {
    }

     /** recoit GET['action'] et lance la suite
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     * @return void
     */
    public function actionReceiver(array $cleanedUpGet): void
    {
        $this->cleanedUpGet = $cleanedUpGet;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'dispAllMedicThemes': // affichage de tous les thèmes médicaux utilisés par le user
                    $this->displayUsedMedicThemes();
                    break;

                case 'dispAllEventsRegrdOneTheme': // affichage de tous les events par rapport à un thème médical
                    $this->displayAllEventsRegardingOneTheme();
                    break;

                default: // si le GET['action'] ne correspond à aucun cas de figure, on repart vers l'affichage de la liste des thèmes
                    echo "<script> window.location.replace('index.php?controller=medic&subCtrlr=medicTheme&action=dispAllMedicThemes') </script>";
            }
        } else { // si le GET['action'] n'est pas défini, on repart vers l'affichage de la liste des thèmes
            echo "<script> window.location.replace('index.php?controller=medic&subCtrlr=medicTheme&action=dispAllMedicThemes') </script>";
        }
    }

    /** Affichage de tous les thèmes médicaux utilisés par le user
    */
    private function displayUsedMedicThemes(): void
    {
        $medicThemeModel = new \HealthKerd\Model\modelInit\medic\medicTheme\MedicThemeModel();
        $medicThemeList = $medicThemeModel->selectMedicThemeByUserId();

        $medicThemeView = new \HealthKerd\View\medic\medicTheme\medicThemeList\MedicThemeListPageBuilder();
        $medicThemeView->buildOrder($medicThemeList);
    }

    /** Affichage de tous les events par rapport à un thème médical
    */
    private function displayAllEventsRegardingOneTheme(): void
    {
        $eventFinderAndGathererController = new \HealthKerd\Controller\medic\eventsFinderAndGatherer\EventsFinderAndGathererGetController();
        $processedData = $eventFinderAndGathererController->actionReceiver('eventsIdsFromMedicThemeId', $this->cleanedUpGet);

        $medicThemeView = new \HealthKerd\View\medic\medicTheme\allEventsRegrdOneTheme\AllEventsRegrdOneThemePageBuilder();
        $medicThemeView->buildOrder($processedData);
    }
}
