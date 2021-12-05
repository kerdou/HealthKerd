<?php

namespace HealthKerd\Controller\medic\doc;

/** Controleur de la section 'accueil' */
class DocGetController extends DocCommonController
{
    private object $docView;
    private array $cleanedUpGet;
    private array $docList;
    private array $speMedicList = array();
    private array $docOfficeList = array();

    /** */
    public function __construct()
    {
        parent::__construct();
        $this->docList = array();
        $this->speMedicList = array();
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
                case 'allDocsListDisp':
                    $this->displayAllDocList();
                    break;
                case 'dispOneDoc':
                    $docID = $cleanedUpGet['docID'];
                    $this->displayOneDoc($docID);
                    break;
                case 'showEventsWithOneDoc':
                    $docID = $cleanedUpGet['docID'];
                    $this->showEventsWithOneDoc($docID);
                    break;
                default:
                    $this->displayAllDocList();
            }
        } else {
            $this->displayAllDocList();
        }
    }


    /** */
    private function displayAllDocList()
    {
        $this->docList = $this->docModel->gatherAllDocs();

        foreach ($this->docList as $key => $value) {
            $this->docList[$key]['fullNameSentence'] = '';
            $this->docList[$key]['speMedicList'] = array();
        }

        $docIDList = $this->docIDsExtractor($this->docList);
        $this->speMedicList = $this->docModel->gatherDocSpeMedicRelation($docIDList);
        $this->docTitleAddition();
        $this->docFullNameSentenceCreator();
        $this->speMedicAdditionToDocs();
        $speMedicBadgeList = $this->speMedicBadgeListBuildUp();

        $this->docView = new \HealthKerd\View\medic\doc\docList\DocListPageBuilder();
        $this->docView->dataReceiver($this->docList, $speMedicBadgeList);
    }


    /** */
    private function displayOneDoc(string $docID)
    {
        $mixedDataResult = $this->docModel->getOneDoc($docID);

        array_push($this->docList, $mixedDataResult['doc']);
        $this->speMedicList = $mixedDataResult['speMedic'];
        $this->docOfficeList = $mixedDataResult['docOffice'];

        foreach ($this->docList as $key => $value) {
            $this->docList[$key]['fullNameSentence'] = '';
            $this->docList[$key]['speMedicList'] = array();
            $this->docList[$key]['docOfficeList'] = array();
        }

        $this->docTitleAddition();
        $this->docFullNameSentenceCreator();
        $this->speMedicAdditionToDocs();
        $this->docOfficeAddition();

        //echo '<pre>';
        //print_r($mixedDataResult);
        //print_r($this->docList);
        //echo '</pre>';

        $this->docView = new \HealthKerd\View\medic\doc\oneDoc\OneDocPageBuilder();
        $this->docView->dataReceiver($this->docList[0]);
    }

    /** */
    private function docIDsExtractor(array $docList)
    {
        $docIDList = array();

        foreach ($docList as $doc) {
            array_push($docIDList, $doc['docID']);
        }

        return $docIDList;
    }


    /**
     *
     */
    private function showEventsWithOneDoc(string $docID)
    {
        date_default_timezone_set('Europe/Paris');
        $medicEventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicArrayBuildOrder();
        $this->docView = new \HealthKerd\View\medic\doc\eventsWithOneDoc\EventsWithOneDocPageBuilder();

        $medicEventsIdResult = array();
        $medicEventsIdResult = $medicEventIdFinder->eventsIdsFromOneDocId($docID);

        // conversion des ID d'event en integer
        $medicEventsIdList = array();
        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        $medicEvtOriginalDataStore = $medicEventDataGatherer->eventIdReceiver($medicEventsIdList);
        $medicEvtProcessedDataStore = $medicEventArrayBuildOrder->eventDataReceiver($medicEvtOriginalDataStore);

        //echo '<pre>';
        //print_r($medicEvtOriginalDataStore);
        //echo '</pre>';

        // vidage de $medicEvtOriginalDataStore
        unset($medicEvtOriginalDataStore);
        $medicEvtOriginalDataStore = array();

        $this->docView->dataReceiver($medicEvtProcessedDataStore);
    }


    /**
     *
     */
    private function docTitleAddition()
    {
        foreach ($this->docList as $docKey => $docValue) {
            switch ($docValue['title']) {
                case 'dr':
                    $this->docList[$docKey]['title'] = 'Dr ';
                    break;
                case 'mr':
                    $this->docList[$docKey]['title'] = 'Mr ';
                    break;
                case 'mrs':
                    $this->docList[$docKey]['title'] = 'Mme ';
                    break;
                case 'ms':
                    $this->docList[$docKey]['title'] = 'Mlle ';
                    break;
                case 'none':
                    $this->docList[$docKey]['title'] = '';
                    break;
            }
        }
    }


    /**
     *
     */
    private function docFullNameSentenceCreator()
    {
        foreach ($this->docList as $docKey => $docValue) {
            $titleType = '';
            $hasFirstName = false;

            // on vérifie le titre, seuls les cas des 'dr' et des 'none' sont vraiment ciblés
            if ($docValue['title'] == 'Dr ') {
                $titleType = 'dr';
            } elseif ($docValue['title'] == '') {
                $titleType = 'none';
            } else {
                $titleType = 'other';
            }

            // on vérifie la présence du prénom
            if (strlen($docValue['firstName']) > 0) {
                $hasFirstName = true;
            } else {
                $hasFirstName = false;
            }

            // les tests se déclenchent uniquement si ce n'est pas un docID == 0

            if ($titleType == 'dr' && $hasFirstName == true) {
                // si le title est 'dr' et qu'il y a un prénom alors 'fullNameSentence' = title + prénom + nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['title'] .
                ucwords($this->docList[$docKey]['firstName']) .
                ' ' .
                ucwords($this->docList[$docKey]['lastName']);
            } elseif ($titleType == 'dr' && $hasFirstName == false) {
                // si title est 'dr' mais qu'il n'y a pas de prénom alors 'fullNameSentence' = title + nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['title'] .
                ucwords($this->docList[$docKey]['lastName']);
            } elseif ($titleType == 'other' && $hasFirstName == true) {
                // si pas 'dr' mais présence de prénom alors 'fullNameSentence' = prénom + nom
                $this->docList[$docKey]['fullNameSentence'] =
                ucwords($this->docList[$docKey]['firstName']) .
                ' ' .
                ucwords($this->docList[$docKey]['lastName']);
            } elseif ($titleType == 'other' && $hasFirstName == false) {
                // si pas de 'dr' mais absence de prénom alors 'fullNameSentence' = title + nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['title'] .
                ucwords($this->docList[$docKey]['lastName']);
            } elseif ($titleType == 'none' && $hasFirstName == true) {
                // si le title est 'none' et qu'il y a un prénom alors 'fullNameSentence' = prénom + nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['firstName'] .
                ' ' .
                $this->docList[$docKey]['lastName'];
            } elseif ($titleType = 'none' && $hasFirstName == false) {
                // si le title est 'none' et qu'il n'y pas de prénom  alors 'fullNameSentence' = nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['lastName'];
            }
        }
    }


    /** */
    private function speMedicAdditionToDocs()
    {
        foreach ($this->docList as $docKey => $docValue) {
            foreach ($this->speMedicList as $speKey => $speValue) {
                if ($docValue['docID'] == $speValue['docID']) {
                    array_push($this->docList[$docKey]['speMedicList'], $speValue);
                }
            }
        }
    }


    /** */
    private function docOfficeAddition()
    {
        foreach ($this->docList as $docKey => $docValue) {
            foreach ($this->docOfficeList as $officeKey => $officeValue) {
                if ($docValue['docID'] == $officeValue['docID']) {
                    array_push($this->docList[$docKey]['docOfficeList'], $officeValue);
                }
            }
        }
    }

    /** */
    private function speMedicBadgeListBuildUp()
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
}
