<?php

namespace HealthKerd\Controller\medic\doc;

/** Controleur de la section 'accueil' */
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


    public function actionReceiver(array $cleanedUpGet, array $cleanedUpPost)
    {
        $this->cleanedUpGet = $cleanedUpGet;
        $this->cleanedUpPost = $cleanedUpPost;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'addDoc':
                    $checksArray = array();
                    $this->docFormChecker = new \HealthKerd\Controller\medic\doc\DocFormChecker();
                    $checksArray = $this->docFormChecker->docFormChecks($this->cleanedUpPost);

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

                case 'editDoc':
                    $checksArray = array();
                    $this->docFormChecker = new \HealthKerd\Controller\medic\doc\DocFormChecker();
                    $checksArray = $this->docFormChecker->docFormChecks($this->cleanedUpPost);

                    if (in_array(false, $checksArray)) {
                        $docView = new \HealthKerd\View\medic\doc\docForm\DocFailedEditFormPageBuilder();
                        $docView->dataReceiver($this->cleanedUpPost, $checksArray, $this->cleanedUpGet['docID']);
                    } else {
                        $pdoErrorMessage = $this->docPostModel->editDoc($this->cleanedUpPost, $this->cleanedUpGet['docID']);
                        echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $this->cleanedUpGet['docID'] . "';</script>";
                    }



                    break;

                case 'removeDoc':
                    $this->docPostModel->deleteOfficeAndSpeMedic($this->cleanedUpGet['docID']);
                    $pdoErrorMessage = $this->docPostModel->deleteDoc($this->cleanedUpGet['docID']);
                    echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
                    break;

                default:
                    echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
            }
        } else {
            echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
        }
    }
}
