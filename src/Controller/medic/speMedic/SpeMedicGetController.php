<?php

namespace HealthKerd\Controller\medic\speMedic;

/** Controleur GET des spécialités médicales */
class SpeMedicGetController
{
    private array $cleanedUpGet;
    private array $speMedicList = array();

    private object $eventIdFinder;
    private object $speMedicModel;
    private object $speView;


    public function __construct()
    {
        $this->eventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $this->speMedicModel = new \HealthKerd\Model\medic\speMedic\SpeMedicModel();
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

    /** création d'une liste de spécialités médicales sans doublons avec leurs ID
     * @return array contient les spécialités médicales (sans doublon) avec leur ID
    */
    private function speMedicListBuildUp()
    {
        $speMedicIDList = array();

        foreach ($this->speMedicList as $key => $value) {
            array_push($speMedicIDList, $value['speMedicID']);
        }

        $speMedicIDList = array_unique($speMedicIDList);
        sort($speMedicIDList, SORT_NUMERIC); // impératif pour que les index s'incrémentent de +1 à chaque élement, sinon le foreach utilisant array_key_exists() ne peut pas marcher

        $speMedicBadgeList = array();

        // s'il y a un match entre le speMedicID et que cette spé n'a pas déjà été ajoutée à $speMedicBadgeList, on l'ajoute
        foreach ($speMedicIDList as $idKey => $idValue) {
            foreach ($this->speMedicList as $speKey => $speValue) {
                if (($idValue == $speValue['speMedicID']) && (array_key_exists($idKey, $speMedicBadgeList) == false)) {
                    $tempArray = [
                        'speMedicID' => $speValue['speMedicID'],
                        'speMedicName' => $speValue['name']
                    ];
                    array_push($speMedicBadgeList, $tempArray);
                }
            }
        }

        //var_dump($speMedicBadgeList);
        return $speMedicBadgeList;
    }

    /** Affichage de toutes les spécialités médicales
    */
    private function displayAllSpeMedics()
    {
        $medicEventsIdResult = $this->eventIdFinder->eventsIdsByUserId();
        $medicEventsIdList = array();

        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        $this->speMedicList = $this->speMedicModel->gatherMedicEventSpeMedicRelation($medicEventsIdList);
        $speMedicUniqueList = $this->speMedicListBuildUp();

        $this->speView = new \HealthKerd\View\medic\speMedic\speMedicList\SpeMedicListPageBuilder();
        $this->speView->dataReceiver($speMedicUniqueList);
    }

    /** Affichage de tous les events vis à vis d'une spé en particulier
     */
    private function dispAllEventsRegardingOneSpeMedic()
    {
        $speMedicID = $this->cleanedUpGet['speMedicID']; // ID de la spéMedic recherchée

        // liste de tous les events du user
        $medicEventsIdResult = $this->eventIdFinder->eventsIdsByUserId();
        $medicEventsIdList = array();
        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        // récupération de toutes les relation events<>speMedic
        $speMedicRelationResult = $this->speMedicModel->speMedicByEventsIds($medicEventsIdList);

        // on ne garde que les medicEvents correspondants au speMedicID
        $medicEventsIdList = array();
        foreach ($speMedicRelationResult as $speValue) {
            if ($speMedicID == $speValue['speMedicID']) {
                array_push($medicEventsIdList, intval($speValue['medicEventID']));
            }
        }
        $medicEventsIdList = array_unique($medicEventsIdList, SORT_NUMERIC);
        sort($medicEventsIdList, SORT_NUMERIC);

        $medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $medicEvtOriginalDataStore = $medicEventDataGatherer->eventIdReceiver($medicEventsIdList);

        $medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicEventArrayBuildOrder();
        $medicEvtProcessedDataStore = $medicEventArrayBuildOrder->eventDataReceiver($medicEvtOriginalDataStore);

        // vidage de $medicEvtOriginalDataStore
        unset($medicEvtOriginalDataStore);
        $medicEvtOriginalDataStore = array();

        //echo '<pre>';
        //print_r($medicEvtProcessedDataStore);
        //echo '</pre>';

        //var_dump($medicEvtProcessedDataStore);

        $this->speView = new \HealthKerd\View\medic\speMedic\AllEventsRegrdOneSpe\AllEventsRegardOneSpePageBuilder();
        $this->speView->dataReceiver($medicEvtProcessedDataStore);
    }
}
