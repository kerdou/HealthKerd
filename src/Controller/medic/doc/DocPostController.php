<?php

namespace HealthKerd\Controller\medic\doc;

/** Controleur de la section 'accueil' */
class DocPostController extends DocCommonController
{
    private array $cleanedUpGet = array();
    private array $cleanedUpPost = array();

    private object $docPostModel;


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
                    $pdoErrorMessage = $this->docPostModel->addDoc($this->cleanedUpPost);

                    $newDocIDArray = $this->docModel->getNewDocID();
                    $newDocID = $newDocIDArray['docID'];

                    if (strlen($pdoErrorMessage) == 0) {
                        $this->docPostModel->addOfficeAndSpeMedic($newDocID);
                    }


                    echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $newDocID . "';</script>";
                    break;

                case 'editDoc':
                    $pdoErrorMessage = $this->docPostModel->editDoc($this->cleanedUpPost, $this->cleanedUpGet['docID']);

                    echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $this->cleanedUpGet['docID'] . "';</script>";
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
