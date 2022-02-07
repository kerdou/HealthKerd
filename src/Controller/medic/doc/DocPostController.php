<?php

namespace HealthKerd\Controller\medic\doc;

/** Controller gérant les données POST des formulaires de docteurs */
class DocPostController extends DocCommonController
{
    private array $cleanedUpGet = array();
    private array $cleanedUpPost = array();

    private object $docPostModel;
    private object $docFormChecker;


    public function __construct()
    {
        parent::__construct();
        $this->docPostModel = new \HealthKerd\Model\medic\doc\DocPostModel();
    }

    public function __destruct()
    {
    }

    /** Recoit les GET['action'] pour determiner l'action à suivre et utilise les données de POST
     * @param array $cleanedUpGet       Données nettoyées du GET
     * @param array $cleanedUpPost      Données nettoyées du POST
     */
    public function actionReceiver(array $cleanedUpGet, array $cleanedUpPost)
    {
        $this->cleanedUpGet = $cleanedUpGet;
        $this->cleanedUpPost = $cleanedUpPost;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'addDoc': // ajout d'un docteur
                    // vérification des données contenues dans le POST
                    $checksArray = array();
                    $this->docFormChecker = new \HealthKerd\Controller\medic\doc\DocFormChecker();
                    $checksArray = $this->docFormChecker->docFormChecks($this->cleanedUpPost);

                    // si $checksArray contient des erreurs (des false), on réaffich le formulaire en indiquant les champs à modifier
                    if (in_array(false, $checksArray)) {
                        $docView = new \HealthKerd\View\medic\doc\docForm\DocFailedAddFormPageBuilder();
                        $docView->dataReceiver($this->cleanedUpPost, $checksArray);
                    } else {
                        $pdoErrorMessage = $this->docPostModel->addDoc($this->cleanedUpPost);

                        $newDocIDArray = $this->docModel->getNewDocID();
                        $newDocID = $newDocIDArray['docID'];

                        if (strlen($pdoErrorMessage) == 0) {
                            $this->docPostModel->addOfficeAndSpeMedic($newDocID);
                        }

                        echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $newDocID . "';</script>";
                    }
                    break;

                case 'editDoc': // modification d'un docteur
                    // vérification des données contenues dans le POST
                    $checksArray = array();
                    $this->docFormChecker = new \HealthKerd\Controller\medic\doc\DocFormChecker();
                    $checksArray = $this->docFormChecker->docFormChecks($this->cleanedUpPost);

                    // si $checksArray contient des erreurs (des false), on réaffich le formulaire en indiquant les champs à modifier
                    if (in_array(false, $checksArray)) {
                        $docView = new \HealthKerd\View\medic\doc\docForm\DocFailedEditFormPageBuilder();
                        $docView->dataReceiver($this->cleanedUpPost, $checksArray, $this->cleanedUpGet['docID']);
                    } else {
                        $pdoErrorMessage = $this->docPostModel->editDoc($this->cleanedUpPost, $this->cleanedUpGet['docID']);
                        echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $this->cleanedUpGet['docID'] . "';</script>";
                    }
                    break;

                case 'removeDoc': // suppr d'un docteur
                    $this->docPostModel->deleteOfficeAndSpeMedic($this->cleanedUpGet['docID']);
                    $pdoErrorMessage = $this->docPostModel->deleteDoc($this->cleanedUpGet['docID']);
                    echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
                    break;

                default: // si GET['action'] ne correspond à aucun cas de figure, on repart à la liste de tous les docteurs
                    echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
            }
        } else { // si GET['action'] n'est pas défini, on repart à la liste de tous les docteurs
            echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
        }
    }
}
