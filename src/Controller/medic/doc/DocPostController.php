<?php

namespace HealthKerd\Controller\medic\doc;

/** Controller gérant les données POST des formulaires de docteurs */
class DocPostController
{
    private array $cleanedUpGet = array();
    private array $cleanedUpPost = array();

    public function __destruct()
    {
    }

    /** Recoit les GET['action'] pour determiner l'action à suivre et utilise les données de POST
     * @param array $cleanedUpGet       Données nettoyées du GET
     * @param array $cleanedUpPost      Données nettoyées du POST
     */
    public function actionReceiver(array $cleanedUpGet, array $cleanedUpPost): void
    {
        $this->cleanedUpGet = $cleanedUpGet;
        $this->cleanedUpPost = $cleanedUpPost;

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'addDoc': // ajout d'un docteur
                    $this->addDoc();
                    break;

                case 'editGeneralDoc': // modifications générales d'un docteur
                    $this->editGeneralDoc();
                    break;

                case 'editSpeMedDocOfficeForDoc': // modification des spé médicales et doc office d'un doc
                    $this->editSpeMedDocOfficeForDoc();
                    break;

                case 'removeDoc': // suppr d'un docteur
                    $this->removeDoc();
                    break;

                default: // si GET['action'] ne correspond à aucun cas de figure, on repart à la liste de tous les docteurs
                    echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
            }
        } else { // si GET['action'] n'est pas défini, on repart à la liste de tous les docteurs
            echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
        }
    }

    /** Ajout d'un docteur
     */
    private function addDoc(): void
    {
        // vérification des données contenues dans le POST
        $checksArray = array();
        $docFormChecker = new \HealthKerd\Controller\medic\doc\DocFormChecker();
        $checksArray = $docFormChecker->docFormChecks($this->cleanedUpPost);

        // si $checksArray contient des erreurs (des false), on réaffich le formulaire en indiquant les champs à modifier
        if (in_array(false, $checksArray)) {
            $docView = new \HealthKerd\View\medic\doc\generalDocForm\DocFailedAddFormPageBuilder();
            $docView->buildOrder($this->cleanedUpPost, $checksArray);
        } else {
            $insertModel = new \HealthKerd\Model\modelInit\medic\doc\DocInsertModel();
            $pdoErrorMessage = $insertModel->addDocModel($this->cleanedUpPost);

            $selectModel = new \HealthKerd\Model\modelInit\medic\doc\DocSelectModel();
            $newDocID = $selectModel->getNewDocIDModel();

            $_SESSION['checkedDocID'] = $newDocID;
            echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=showDocEditSpeMedDocOfficeForm&docID=" . $newDocID . "';</script>";
        }
    }

    /** Modifications générales d'un docteur
     */
    private function editGeneralDoc(): void
    {
        // vérification des données contenues dans le POST
        $checksArray = array();
        $docFormChecker = new \HealthKerd\Controller\medic\doc\DocFormChecker();
        $checksArray = $docFormChecker->docFormChecks($this->cleanedUpPost);

        // si $checksArray contient des erreurs (des false), on réaffiche le formulaire en indiquant les champs à modifier
        if (in_array(false, $checksArray)) {
            $docView = new \HealthKerd\View\medic\doc\generalDocForm\DocFailedEditFormPageBuilder();
            $docView->buildOrder($this->cleanedUpPost, $checksArray, $this->cleanedUpGet['docID']);
        } else {
            $docUpdateModel = new \HealthKerd\Model\modelInit\medic\doc\DocUpdateModel();
            $pdoErrorMessage = $docUpdateModel->editDocModel($this->cleanedUpPost, $this->cleanedUpGet['docID']);
            echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $this->cleanedUpGet['docID'] . "';</script>";
        }
    }

    /** Modification des spé médicales et doc office d'un doc
     */
    private function editSpeMedDocOfficeForDoc(): void
    {
        $speMedicIDArray = [];
        $docOfficeIDArray = [];

        // récupération des ID de spemedic et de doc office
        foreach ($this->cleanedUpPost as $key => $value) {
            if (str_contains($key, 'speMedicID_')) {
                array_push($speMedicIDArray, $value);
            }

            if (str_contains($key, 'docOfficeID_')) {
                array_push($docOfficeIDArray, $value);
            }
        }

        $docUpdateModel = new \HealthKerd\Model\modelInit\medic\doc\DocUpdateModel();
        $docUpdateModel->editSpeMedForDocModel($speMedicIDArray);
        $docUpdateModel->editDocOfficeForDocModel($docOfficeIDArray);
    }

    /** Suppression d'un docteur
     */
    private function removeDoc(): void
    {
        $docDeleteModel = new \HealthKerd\Model\modelInit\medic\doc\DocDeleteModel();
        $pdoErrorMessage = $docDeleteModel->deleteDoc($this->cleanedUpGet['docID']);
        echo "<script>window.location = 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp';</script>";
    }
}
