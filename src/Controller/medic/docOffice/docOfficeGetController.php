<?php

namespace HealthKerd\Controller\medic\docOffice;

/** Contrôleur GET des cabinets médicaux
 */
class DocOfficeGetController
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
                case 'allDocOfficesListDisp': // affichage de la liste des cabinets médicaux
                    $this->displayAllDocOfficesList();
                    break;

                case 'dispEventsWithOneDocOffice': // affichage des events liés à un cabinet médical en particulier
                    $this->displayEventsWithOneDocOffice();
                    break;

                default: // si GET['action'] ne correspond à aucun cas de figure, on repart vers la liste des cabinets médicaux
                    $this->displayAllDocOfficesList();
            }
        } else {
            // si GET['action'] n'est pas défini, on repart vers la liste des cabinets médicaux
            $this->displayAllDocOfficesList();
        }
    }

    /** Affichage de la liste des cabinets médicaux
     */
    private function displayAllDocOfficesList(): void
    {
        $docOfficeModel = new \HealthKerd\Model\modelInit\medic\docOffice\DocOfficeSelectModel();
        $docOfficeList = $docOfficeModel->gatherAllDocOfficesModel();

        $docOfficeView = new \HealthKerd\View\medic\docOffice\docOfficeList\DocOfficeListPageBuilder();
        $docOfficeView->buildOrder($docOfficeList);
    }

    /** Affichage des events liés à un cabinet médical en particulier
    */
    private function displayEventsWithOneDocOffice(): void
    {
        $eventFinderAndGathererController = new \HealthKerd\Controller\medic\eventsFinderAndGatherer\EventsFinderAndGathererGetController();
        $processedData = $eventFinderAndGathererController->actionReceiver('eventsIdsByDocOfficeId', $this->cleanedUpGet);

        $docOfficeView = new \HealthKerd\View\medic\docOffice\allEventsRegrdOneDocOffice\AllEventsRegrdOneDocOfficePageBuilder();
        $docOfficeView->buildOrder($processedData);
    }
}
