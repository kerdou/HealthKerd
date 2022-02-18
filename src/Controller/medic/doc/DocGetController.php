<?php

namespace HealthKerd\Controller\medic\doc;

/** Contrôleur GET des docteurs
 */
class DocGetController extends DocGetControllerFunctionsPool
{
    protected array $cleanedUpGet = array();
    protected array $docList = array();
    protected array $speMedicList = array();
    protected array $docOfficeList = array();
    protected array $medicEventList = array();
    protected array $pastEvents = array();
    protected array $futureEvents = array();
    protected array $dataWorkbench = array();

    protected object $docSelectModel;
    protected object $docView;

    public function __construct()
    {
        $this->docSelectModel = new \HealthKerd\Model\modelInit\medic\doc\DocSelectModel();
    }

    public function __destruct()
    {
    }

    /** recoit GET['action'] et lance la suite
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     */
    public function actionReceiver(array $cleanedUpGet): void
    {
        $this->cleanedUpGet = $cleanedUpGet;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'allDocsListDisp': // affichage de la liste de tous les docs du user
                    $this->displayAllDocList();
                    break;

                case 'dispOneDoc': // affichage de la fiche d'un seul doc
                    $docID = $cleanedUpGet['docID'];
                    $this->displayOneDoc($docID);
                    break;

                case 'showEventsWithOneDoc': // affichage de tous les events liés à un doc
                    $this->showEventsWithOneDoc();
                    break;

                case 'showDocAddForm': // affichage du formulaire d'ajout de doc
                    $this->showDocAddForm();
                    break;

                case 'showDocEditForm': // affichage du formulaire de modif de doc
                    $docID = $cleanedUpGet['docID'];
                    $this->showDocEditForm($docID);
                    break;

                case 'showDocDeleteForm': // affichage du formulaire de suppr de doc
                    $docID = $cleanedUpGet['docID'];
                    $this->showDocDeleteForm($docID);
                    break;

                default:
                    // si le Get['action'] ne correspond à rien, retour à l'affichage de la liste des docs
                    $this->displayAllDocList();
            }
        } else {
            // si le Get['action'] n'est pas défini, retour à l'affichage de la liste des docs
            $this->displayAllDocList();
        }
    }

    /** Affichage de la liste de tous les docs du user et des spécialités médicales utilisées
    */
    private function displayAllDocList(): void
    {
        $combinedData = array();
        $combinedData = $this->docSelectModel->displayAllDocsListModel();

        $DocListOrganizerForDocListing = new \HealthKerd\Processor\medic\doc\DocListOrganizerForDocListing();
        $organizedData = $DocListOrganizerForDocListing->docListOrganizer($combinedData['doc_list'], $combinedData['doc_spemedic_relation']);

        $this->docView = new \HealthKerd\View\medic\doc\docList\DocListPageBuilder();
        $this->docView->dataReceiver($organizedData, $combinedData['used_spemedics']); // view
    }

    /** Affichage de la fiche d'un seul doc
     * @param string $docID
    */
    private function displayOneDoc(string $docID): void
    {
        $mixedDataResult = $this->docSelectModel->getDataForOneDocPageModel($docID); // model

        array_push($this->docList, $mixedDataResult['doc']);
        $this->speMedicList = $mixedDataResult['speMedic'];
        $this->docOfficeList = $mixedDataResult['docOffice'];
        $this->medicEventList = $mixedDataResult['medicEvent'];

        // préparation à l'ajout de données du doc:
        foreach ($this->docList as $key => $value) {
            $this->docList[$key]['fullNameSentence'] = ''; // titre et nom complet du doc
            $this->docList[$key]['speMedicList'] = array(); // spécialités médicales
            $this->docList[$key]['docOfficeList'] = array(); // mliste des cabinets médicaux
            $this->docList[$key]['medicEvent'] = array(); // liste des events
        }

        // pour le doc
        $this->docTitleAddition();
        $this->docFullNameSentenceCreator();

        // pour les speMedics
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
        $this->docView->dataReceiver($this->docList[0]); // view
    }

    /** Affichage de tous les events liés à un doc
    */
    private function showEventsWithOneDoc(): void
    {
        $this->eventFinderAndGathererController = new \HealthKerd\Controller\medic\eventsFinderAndGatherer\EventsFinderAndGathererGetController();
        $processedData = $this->eventFinderAndGathererController->actionReceiver('eventsIdsFromOneDocId', $this->cleanedUpGet);

        $this->docView = new \HealthKerd\View\medic\doc\eventsWithOneDoc\EventsWithOneDocPageBuilder();
        $this->docView->dataReceiver($processedData);
    }

    /** Affichage du formulaire d'ajout de doc
    */
    private function showDocAddForm(): void
    {
        $this->docView = new \HealthKerd\View\medic\doc\docForm\DocAddFormPageBuilder();
        $this->docView->dataReceiver();
    }

    /** Affichage du formulaire de modif de doc
     * @param string $docID
    */
    private function showDocEditForm(string $docID): void
    {
        $docData = $this->docSelectModel->getAllDataForOneDocFromDocListModel($docID);

        $this->docView = new \HealthKerd\View\medic\doc\docForm\DocEditFormPageBuilder();
        $this->docView->dataReceiver($docData);
    }

    /** Affichage du formulaire de suppr de doc
     * @param string $docID
    */
    private function showDocDeleteForm(string $docID): void
    {
        $docData = $this->docSelectModel->getAllDataForOneDocFromDocListModel($docID);

        $this->docView = new \HealthKerd\View\medic\doc\docForm\DocDeleteFormPageBuilder();
        $this->docView->dataReceiver($docData);
    }
}
