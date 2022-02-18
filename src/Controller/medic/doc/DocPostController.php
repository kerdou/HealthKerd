<?php

namespace HealthKerd\Controller\medic\doc;

/** Controller gérant les données POST des formulaires de docteurs */
class DocPostController
{
    private array $cleanedUpGet = array();
    private array $cleanedUpPost = array();

    private object $docFormChecker;


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
                        $insertModel = new \HealthKerd\Model\modelInit\medic\doc\DocInsertModel();
                        $pdoErrorMessage = $insertModel->addDocModel($this->cleanedUpPost);

                        $selectModel = new \HealthKerd\Model\modelInit\medic\doc\DocSelectModel();
                        $newDocID = $selectModel->getNewDocIDModel();

                        if (strlen($pdoErrorMessage) == 0) { // si la création du docteur se passe bien, on lui ajoute ses cabinets médicaux et ses spé médicales
                            $insertModel->addOfficeAndSpeMedicModel($newDocID);
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
                        $docUpdateModel = new \HealthKerd\Model\modelInit\medic\doc\DocUpdateModel();
                        $pdoErrorMessage = $docUpdateModel->editDocModel($this->cleanedUpPost, $this->cleanedUpGet['docID']);
                        echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $this->cleanedUpGet['docID'] . "';</script>";
                    }
                    break;

                case 'removeDoc': // suppr d'un docteur
                    $docDeleteModel = new \HealthKerd\Model\modelInit\medic\doc\DocDeleteModel();
                    $docDeleteModel->deleteOfficeAndSpeMedic($this->cleanedUpGet['docID']);
                    $pdoErrorMessage = $docDeleteModel->deleteDoc($this->cleanedUpGet['docID']);
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
