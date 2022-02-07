<?php

namespace HealthKerd\Controller\medic\docOffice;

/** Contrôleur GET des cabinets médicaux
 */
class DocOfficeGetController extends DocOfficeCommonController
{
    private array $cleanedUpGet;
    private array $docOfficeList = array();
    private array $speMedicList = array();


    private object $medicEventIdFinder; // Modéle spéclialisé dans la recherche d'ID d'events médicaux
    private object $medicEventDataGatherer;
    private object $medicEventArrayBuildOrder;
    private object $docOfficeView;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Paris');
        $this->medicEventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $this->medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $this->medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicEventArrayBuildOrder();
    }

    public function __destruct()
    {
    }

    /** recoit GET['action'] et lance la suite
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     * @return void
     */
    public function actionReceiver(array $cleanedUpGet)
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
    private function displayAllDocOfficesList()
    {
        $this->docOfficeList = $this->docOfficeModel->gatherAllDocOffices();

        $this->docOfficeView = new \HealthKerd\View\medic\docOffice\docOfficeList\DocOfficeListPageBuilder();
        $this->docOfficeView->dataReceiver($this->docOfficeList);
    }

    /** Affichage des events liés à un cabinet médical en particulier
    */
    private function displayEventsWithOneDocOffice()
    {
        $medicEventsIdResult = $this->medicEventIdFinder->eventsIdsByDocOfficeId($this->cleanedUpGet['docOfficeID']);
        $medicEventsIdList = array();

        // conversion des ID d'event en integer
        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        $medicEvtOriginalDataStore = $this->medicEventDataGatherer->eventIdReceiver($medicEventsIdList);
        $medicEvtProcessedDataStore = $this->medicEventArrayBuildOrder->eventDataReceiver($medicEvtOriginalDataStore);

        // vidage de $medicEvtOriginalDataStore
        unset($medicEvtOriginalDataStore);
        $medicEvtOriginalDataStore = array();

        //echo '<pre>';
        //print_r($medicEvtProcessedDataStore);
        //var_dump($medicEvtProcessedDataStore);
        //echo '</pre>';

        $this->docOfficeView = new \HealthKerd\View\medic\docOffice\allEventsRegrdOneDocOffice\AllEventsRegrdOneDocOfficePageBuilder();
        $this->docOfficeView->dataReceiver($medicEvtProcessedDataStore);
    }
}
