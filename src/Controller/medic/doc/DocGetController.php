<?php

namespace HealthKerd\Controller\medic\doc;

/** Contrôleur GET des docteurs
 */
class DocGetController extends DocGetControllerFunctionsPool
{
    protected array $cleanedUpGet = array();
    protected array $docList = array();

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

        $docListOrganizerForDocListing = new \HealthKerd\Processor\medic\doc\DocListOrganizerForDocListing();
        $organizedData = $docListOrganizerForDocListing->docListOrganizer($combinedData['doc_list'], $combinedData['doc_spemedic_relation']);

        $docView = new \HealthKerd\View\medic\doc\docList\DocListPageBuilder();
        $docView->buildOrder($organizedData, $combinedData['used_spemedics']); // view
    }

    /** Affichage de la fiche d'un seul doc
     * @param string $docID
    */
    private function displayOneDoc(string $docID): void
    {
        $mixedDataResult = $this->docSelectModel->getDataForOneDocPageModel($docID); // model

        // Création de la phrase combinant civilité / prénom / nom du docteur
        $docTitleAndNameSentenceBuilder = new \HealthKerd\Services\medic\doc\DocTitleAndNameSentence();
        $fullNameSentence = $docTitleAndNameSentenceBuilder->dataReceiver(
            $docID,
            $mixedDataResult['doc']['title'],
            $mixedDataResult['doc']['firstName'],
            $mixedDataResult['doc']['lastName']
        );

        // ajout des données temporelles des events
        foreach ($mixedDataResult['medicEvent'] as $key => $value) {
            $DateAndTimeManagement = new \HealthKerd\Services\common\DateAndTimeManagement();
            $convertedTimeAndDate = $DateAndTimeManagement->dateAndTimeConverter(
                $value['dateTime'],
                $_ENV['DATEANDTIME']['timezoneObj']
            );

            $mixedDataResult['medicEvent'][$key]['time']['dateTime'] = $value['dateTime'];
            $mixedDataResult['medicEvent'][$key]['time']['timestamp'] = $convertedTimeAndDate['timestamp'];
            $mixedDataResult['medicEvent'][$key]['time']['frenchDate'] = $convertedTimeAndDate['frenchDate'];
            $mixedDataResult['medicEvent'][$key]['time']['time'] = $convertedTimeAndDate['time'];
        }

        // dispatch des events suivants qu'ils appartiennent au passé ou au futur
        $timestampDispatcher = new \HealthKerd\Services\common\TimestampDispatcher();
        $mixedDataResult['medicEvent'] = $timestampDispatcher->timestampDispatcher($mixedDataResult['medicEvent']);

        // tri des events passés et futurs par ordre croissant de timestamp
        $timestampSorter = new \HealthKerd\Services\common\TimestampSorting();
        $mixedDataResult['medicEvent']['past'] = $timestampSorter->incrTimestampSortLauncher($mixedDataResult['medicEvent']['past']);
        $mixedDataResult['medicEvent']['future'] = $timestampSorter->incrTimestampSortLauncher($mixedDataResult['medicEvent']['future']);


        $this->docList = $mixedDataResult['doc'];
        $this->docList['fullNameSentence'] = $fullNameSentence; // titre et nom complet du doc
        $this->docList['speMedicList'] = $mixedDataResult['speMedic']; // spécialités médicales
        $this->docList['docOfficeList'] = $mixedDataResult['docOffice']; // mliste des cabinets médicaux
        $this->docList['medicEvent'] = $mixedDataResult['medicEvent']; // liste des events

        $this->eventsSummaryCreation($mixedDataResult['medicEvent']);

        $docView = new \HealthKerd\View\medic\doc\oneDoc\OneDocPageBuilder();
        $docView->buildOrder($this->docList); // view
    }

    /** Affichage de tous les events liés à un doc
    */
    private function showEventsWithOneDoc(): void
    {
        $eventFinderAndGathererController = new \HealthKerd\Controller\medic\eventsFinderAndGatherer\EventsFinderAndGathererGetController();
        $processedData = $eventFinderAndGathererController->actionReceiver('eventsIdsFromOneDocId', $this->cleanedUpGet);

        $docView = new \HealthKerd\View\medic\doc\eventsWithOneDoc\EventsWithOneDocPageBuilder();
        $docView->buildOrder($processedData);
    }

    /** Affichage du formulaire d'ajout de doc
    */
    private function showDocAddForm(): void
    {
        $docView = new \HealthKerd\View\medic\doc\docForm\DocAddFormPageBuilder();
        $docView->buildOrder();
    }

    /** Affichage du formulaire de modif de doc
     * @param string $docID
    */
    private function showDocEditForm(string $docID): void
    {
        $docData = $this->docSelectModel->getAllDataForOneDocFromDocListModel($docID);

        $docView = new \HealthKerd\View\medic\doc\docForm\DocEditFormPageBuilder();
        $docView->buildOrder($docData);
    }

    /** Affichage du formulaire de suppr de doc
     * @param string $docID
    */
    private function showDocDeleteForm(string $docID): void
    {
        $docData = $this->docSelectModel->getAllDataForOneDocFromDocListModel($docID);

        $docView = new \HealthKerd\View\medic\doc\docForm\DocDeleteFormPageBuilder();
        $docView->buildOrder($docData);
    }
}
