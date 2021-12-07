<?php

namespace HealthKerd\Controller\medic\doc;

/** Controleur de la section 'accueil' */
class DocGetController extends DocGetControllerFunctionsPool
{
    protected array $cleanedUpGet;
    protected array $docList = array();
    protected array $speMedicList = array();
    protected array $docOfficeList = array();
    protected array $medicEventList = array();
    protected array $pastEvents = array();
    protected array $futureEvents = array();
    protected array $dataWorkbench = array();

    protected object $docView;


    /** */
    public function __construct()
    {
        parent::__construct();
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

                case 'showDocAddForm':
                    $this->showDocAddForm();
                    break;

                case 'showDocEditForm':
                    $docID = $cleanedUpGet['docID'];
                    $this->showDocEditForm($docID);
                    break;

                case 'showDocDeleteForm':
                    $docID = $cleanedUpGet['docID'];
                    $this->showDocDeleteForm($docID);
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
        $mixedDataResult = $this->docModel->getOneDocForPageDisplay($docID);

        array_push($this->docList, $mixedDataResult['doc']);
        $this->speMedicList = $mixedDataResult['speMedic'];
        $this->docOfficeList = $mixedDataResult['docOffice'];
        $this->medicEventList = $mixedDataResult['medicEvent'];

        foreach ($this->docList as $key => $value) {
            $this->docList[$key]['fullNameSentence'] = '';
            $this->docList[$key]['speMedicList'] = array();
            $this->docList[$key]['docOfficeList'] = array();
            $this->docList[$key]['medicEvent'] = array();
        }


        // pour le doc
        $this->docTitleAddition();
        $this->docFullNameSentenceCreator();

        // pour les spemdecis
        $this->speMedicAdditionToDocs();

        // pour le docOffice
        $this->docOfficeAddition();

        // pour les medicEvents
        $this->dateAndTimeCreator();
        $this->medicEventArrayTimeModifier();
        $this->timeManagement();
        $this->eventTimeDispatcher();
        $this->pastAndFutureEventsTimeSorting();
        $this->eventsSummaryCreation();

        $this->docView = new \HealthKerd\View\medic\doc\oneDoc\OneDocPageBuilder();
        $this->docView->dataReceiver($this->docList[0]);
    }


    /** */
    private function showDocAddForm()
    {
        $this->docView = new \HealthKerd\View\medic\doc\docForm\DocAddFormPageBuilder();
        $this->docView->dataReceiver();
    }


    /** */
    private function showDocEditForm(string $docID)
    {
        $docData = $this->docModel->getOneDocForFormDisplay($docID);

        $this->docView = new \HealthKerd\View\medic\doc\docForm\DocEditFormPageBuilder();
        $this->docView->dataReceiver($docData);
    }

    /** */
    private function showDocDeleteForm(string $docID)
    {
        $docData = $this->docModel->getOneDocForFormDisplay($docID);

        $this->docView = new \HealthKerd\View\medic\doc\docForm\DocDeleteFormPageBuilder();
        $this->docView->dataReceiver($docData);
    }


}
