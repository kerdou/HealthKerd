<?php

namespace HealthKerd\Controller\medic\doc;

/** Contrôleur GET des docteurs
 */
class DocGetController
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
                    $_SESSION['checkedDocID'] = $cleanedUpGet['docID'];
                    $this->displayOneDoc();
                    break;

                case 'showEventsWithOneDoc': // affichage de tous les events liés à un doc
                    $this->showEventsWithOneDoc();
                    break;

                case 'showDocAddForm': // affichage du formulaire d'ajout de doc
                    $this->showDocAddForm();
                    break;

                case 'showDocEditGeneralForm': // affichage du formulaire général de modif de doc
                    $_SESSION['checkedDocID'] = $cleanedUpGet['docID'];
                    $this->showDocEditGeneralForm();
                    break;

                case 'showDocEditSpeMedDocOfficeForm': // affichage du formulaire de modif de spé medic et doc office
                    $_SESSION['checkedDocID'] = $cleanedUpGet['docID'];
                    $this->showDocEditSpeMedDocOfficeForm();
                    break;

                case 'getFetchDataForSpeMedDocOfficeForm': // récupération des données pour le form de modif de spé medic et doc office
                    // fait suite au case 'showDocEditSpeMedDocOfficeForm'
                    if (isset($_SESSION['checkedDocID'])) {
                        $this->getFetchDataForSpeMedDocOfficeForm();
                    } else {
                        $this->displayAllDocList();
                    }
                    break;

                case 'showDocDeleteForm': // affichage du formulaire de suppr de doc
                    $_SESSION['checkedDocID'] = $cleanedUpGet['docID'];
                    $this->showDocDeleteForm();
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
    */
    private function displayOneDoc(): void
    {
        $mixedDataResult = $this->docSelectModel->getDataForOneDocPageModel($_SESSION['checkedDocID']); // model

        // Création de la phrase combinant civilité / prénom / nom du docteur
        $docTitleAndNameSentenceBuilder = new \HealthKerd\Services\medic\doc\DocTitleAndNameSentence();
        $fullNameSentence = $docTitleAndNameSentenceBuilder->dataReceiver(
            $_SESSION['checkedDocID'],
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
        $mixedDataResult['medicEvent'] = $timestampDispatcher->nowTimestampDispatcher($mixedDataResult['medicEvent']);

        // tri des events passés et futurs par ordre croissant de timestamp
        $timestampSorter = new \HealthKerd\Services\common\TimestampSorting();
        $mixedDataResult['medicEvent']['past'] = $timestampSorter->incrTimestampSortLauncher($mixedDataResult['medicEvent']['past']);
        $mixedDataResult['medicEvent']['future'] = $timestampSorter->incrTimestampSortLauncher($mixedDataResult['medicEvent']['future']);


        $this->docList = $mixedDataResult['doc'];
        $this->docList['fullNameSentence'] = $fullNameSentence; // titre et nom complet du doc
        $this->docList['docSpeMedicList'] = $mixedDataResult['docSpeMedic']; // spécialités médicales du doc
        $this->docList['docOfficeList'] = $mixedDataResult['docOffice']; // liste des cabinets médicaux
        $this->docList['docOfficeSpeMedic'] = $mixedDataResult['docOfficeSpeMedic']; // listes des spécialités médicales des doc offices
        $this->docList['medicEvent'] = $mixedDataResult['medicEvent']; // liste des events

        $this->eventsSummaryCreation($mixedDataResult['medicEvent']);

        $docView = new \HealthKerd\View\medic\doc\oneDoc\OneDocPageBuilder();
        $docView->buildOrder($this->docList); // view
    }


    /** Récapitulatif des nombres et dates d'events passés et futurs
     * @param array $medicEvents        Données des events passés et à venir
     */
    private function eventsSummaryCreation(array $medicEvents): void
    {
        $pastEventsQty = sizeof($medicEvents['past']);
        $futureEventsQty = sizeof($medicEvents['future']);

        $this->docList['medicEvent']['qty']['past'] = $pastEventsQty;
        $this->docList['medicEvent']['qty']['coming'] = $futureEventsQty;
        $this->docList['medicEvent']['qty']['total'] = $pastEventsQty + $futureEventsQty;

        if ($pastEventsQty > 0) {
            $this->docList['medicEvent']['dates']['first'] = $this->docList['medicEvent']['past'][0]['time']['frenchDate'];

            $this->docList['medicEvent']['dates']['last'] = $this->docList['medicEvent']['past'][$pastEventsQty - 1]['time']['frenchDate'];
        } else {
            $this->docList['medicEvent']['dates']['first'] = 'Aucun';
            $this->docList['medicEvent']['dates']['last'] = 'Aucun';
        }

        if ($futureEventsQty > 0) {
            $this->docList['medicEvent']['dates']['next'] = $this->docList['medicEvent']['future'][0]['time']['frenchDate'];
        } else {
            $this->docList['medicEvent']['dates']['next'] = 'Aucun';
        }
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
        $docView = new \HealthKerd\View\medic\doc\generalDocForm\DocAddFormPageBuilder();
        $docView->buildOrder();
    }

    /** Affichage du formulaire de modif de doc
    */
    private function showDocEditGeneralForm(): void
    {
        $docData = $this->docSelectModel->getAllDataForOneDocFromDocListModel($_SESSION['checkedDocID']);

        $docView = new \HealthKerd\View\medic\doc\generalDocForm\DocEditFormPageBuilder();
        $docView->buildOrder($docData);
    }

    /** Affichage du formulaire de spé médicales et de doc office assignés au doc
     */
    private function showDocEditSpeMedDocOfficeForm(): void
    {
        $docView = new \HealthKerd\View\medic\doc\speMedicDocOfficeForm\SpeMedicDocOfficeFormPageBuilder();
        $docView->buildOrder();
    }

    /** Récupération des données pour le form de modif de spé medic et doc office
     * * Fait suite au case 'showDocEditSpeMedDocOfficeForm'
     */
    private function getFetchDataForSpeMedDocOfficeForm(): void
    {
        $this->docSelectModel->getFetchDataForSpeMedDocOfficeForm();
    }

    /** Affichage du formulaire de suppr de doc
    */
    private function showDocDeleteForm(): void
    {
        $docData = $this->docSelectModel->getAllDataForOneDocFromDocListModel($_SESSION['checkedDocID']);

        $docView = new \HealthKerd\View\medic\doc\generalDocForm\DocDeleteFormPageBuilder();
        $docView->buildOrder($docData);
    }
}
