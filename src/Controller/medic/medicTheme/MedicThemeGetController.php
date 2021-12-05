<?php

namespace HealthKerd\Controller\medic\medicTheme;

/** Controleur de la section 'accueil' */
class MedicThemeGetController extends MedicThemeCommonController
{
    private array $cleanedUpGet;
    private array $medicThemeList = array();

    private object $eventIdFinder;
    private object $medicThemeView;

    /** */
    public function __construct()
    {
        parent::__construct();
        $this->eventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
    }

    public function __destruct()
    {
    }

    /** */
    public function actionReceiver(array $cleanedUpGet)
    {
        $this->cleanedUpGet = $cleanedUpGet;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'dispAllMedicThemes':
                    $this->displayAllMedicThemes();
                    break;

                case 'dispAllEventsRegrdOneTheme':
                    $this->displayAllEventsRegardingOneTheme();
                    break;

                default:
                    //$this->displayAllDocList();
            }
        } else {
            //$this->displayAllDocList();
        }
    }





    /** */
    private function displayAllMedicThemes()
    {
        $medicEventsIdResult = $this->eventIdFinder->eventsIdsByUserId();
        $medicEventsIdList = array();

        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        $this->medicThemeList = $this->medicThemeModel->medicThemeByEventsIds($medicEventsIdList);
        $medicThemeUniqueList = $this->medicThemeListBuildUp();

        $this->medicThemeView = new \HealthKerd\View\medic\medicTheme\medicThemeList\MedicThemeListPageBuilder();
        $this->medicThemeView->dataReceiver($medicThemeUniqueList);
    }


    /** */
    private function displayAllEventsRegardingOneTheme()
    {
        $medicThemeID = $this->cleanedUpGet['medicThemeID']; // ID de la spéMedic recherchée

        // liste de tous les events du user
        $medicEventsIdResult = $this->eventIdFinder->eventsIdsByUserId();
        $medicEventsIdList = array();
        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        // récupération de toutes les relation events<>medicTheme
        $medicThemeRelationResult = $this->medicThemeModel->gatherMedicEventMedicThemeRelation($medicEventsIdList);

        // on ne garde que les medicEvents correspondants au speMedicID
        $medicEventsIdList = array();
        foreach ($medicThemeRelationResult as $themeValue) {
            if ($medicThemeID == $themeValue['medicThemeID']) {
                array_push($medicEventsIdList, intval($themeValue['medicEventID']));
            }
        }
        $medicEventsIdList = array_unique($medicEventsIdList, SORT_NUMERIC);
        sort($medicEventsIdList, SORT_NUMERIC);

        $medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $medicEvtOriginalDataStore = $medicEventDataGatherer->eventIdReceiver($medicEventsIdList);

        $medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicArrayBuildOrder();
        $medicEvtProcessedDataStore = $medicEventArrayBuildOrder->eventDataReceiver($medicEvtOriginalDataStore);

        // vidage de $medicEvtOriginalDataStore
        unset($medicEvtOriginalDataStore);
        $medicEvtOriginalDataStore = array();

        //echo '<pre>';
        //print_r($medicEvtProcessedDataStore);
        //echo '</pre>';

        //var_dump($medicEvtProcessedDataStore);

        $this->medicThemeView = new \HealthKerd\View\medic\medicTheme\allEventsRegrdOneTheme\AllEventsRegrdOneThemePageBuilder();
        $this->medicThemeView->dataReceiver($medicEvtProcessedDataStore);
    }


    /** */
    private function medicThemeListBuildUp()
    {
        $medicThemeIDList = array();

        foreach ($this->medicThemeList as $key => $value) {
            array_push($medicThemeIDList, $value['medicThemeID']);
        }

        $medicThemeIDList = array_unique($medicThemeIDList);
        sort($medicThemeIDList, SORT_NUMERIC); // impératif pour que les index s'incrémentent de +1 à chaque élement, sinon le foreach utilisant array_key_exists() ne peut pas marcher

        $medicThemesUniqueList = array();

        // s'il y a un match entre le speMedicID et que cette spé n'a pas déjà été ajoutée à $medicThemesUniqueList, on l'ajoute
        foreach ($medicThemeIDList as $idKey => $idValue) {
            foreach ($this->medicThemeList as $themeKey => $themeValue) {
                if (($idValue == $themeValue['medicThemeID']) && (array_key_exists($idKey, $medicThemesUniqueList) == false)) {
                    $tempArray = [
                        'medicThemeID' => $themeValue['medicThemeID'],
                        'name' => $themeValue['name']
                    ];
                    array_push($medicThemesUniqueList, $tempArray);
                }
            }
        }

        return $medicThemesUniqueList;
    }
}
