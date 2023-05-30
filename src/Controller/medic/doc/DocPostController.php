<?php

namespace HealthKerd\Controller\medic\doc;

/** Controller gérant les données POST des formulaires de docteurs */
class DocPostController
{
    private array $cleanedUpGet = array();
    private array $cleanedUpPost = array();

    private array $fieldsToCheck = array();
    private array $feedbackToForm = array();

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

        // Se déclenche uniquement si le tel n'est pas au format 01.02.03.04.05
        if (strlen($this->cleanedUpPost['tel']) < 14) {
            $this->telNbrReorganizer();
        }

        $this->fieldsToCheck = array(
            'lastname' => $this->cleanedUpPost['lastname'],
            'firstname' => $this->cleanedUpPost['firstname'],
            'tel' => $this->cleanedUpPost['tel'],
            'mail' => $this->cleanedUpPost['mail'],
            'webpage' => $this->cleanedUpPost['webpage'],
            'doctolibpage' => $this->cleanedUpPost['doctolibpage'],
        );

        // ensemble des données à renvoyer au form, doit être completé un peu plus bas
        $this->feedbackToForm = array(
            'checkedInputs' => array(),
            'formIsValid' => false,
            'feedbackFromDB' => 'aborted',
            'newDocID' => -1
        );

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

    /** Réorganise l'agencement des numéros de tel pour avoir le format 01.02.03.04.05
     */
    private function telNbrReorganizer()
    {
        $teltemp = str_replace('.', '', $this->cleanedUpPost['tel']);
        $teltemp = str_split($teltemp, 2);
        $teltemp = implode('.', $teltemp);
        $this->cleanedUpPost['tel'] = $teltemp;
    }

    /** Ajout d'un docteur
     */
    private function addDoc(): void
    {
        // vérification des données contenues dans le POST
        $docFormChecker = new \HealthKerd\Controller\medic\doc\DocFormChecker2();
        $formChecksFeedback = $docFormChecker->docFormChecks($this->fieldsToCheck);
        $overallVerdictsArr = array(); // recoit les verdicts finaux des test

        // extraction des verdicts finaux des tests dans $overallVerdictsArr
        // et remplissage des checkedInputs dans $feedbackToForm
        foreach ($formChecksFeedback as $field => $element) {
            array_push($overallVerdictsArr, $element['overallValidityVerdict']);

            $this->feedbackToForm['checkedInputs'][$field]['checksVerdicts'] = $element['checksVerdicts'];
            $this->feedbackToForm['checkedInputs'][$field]['overallValidityVerdict'] = $element['overallValidityVerdict'];
        }

        // s'il y a le moindre souci dans les checks on renvoie le résultat des tests au form, sinon on poursuit
        if (in_array(false, $overallVerdictsArr)) {
            echo json_encode($this->feedbackToForm);
        } else {
            $this->feedbackToForm['formIsValid'] = true;

            $insertModel = new \HealthKerd\Model\modelInit\medic\doc\DocInsertModel();
            $pdoFeedback = $insertModel->addDocModel($this->cleanedUpPost);

            if (strlen($pdoFeedback) == 0) {
                $this->feedbackToForm['feedbackFromDB'] = 'success';

                $selectModel = new \HealthKerd\Model\modelInit\medic\doc\DocSelectModel();
                $newDocID = $selectModel->getNewDocIDModel();

                $_SESSION['checkedDocID'] = $newDocID;
                $this->feedbackToForm['newDocID'] = $newDocID;
            } else {
                $this->feedbackToForm['feedbackFromDB'] = 'fail';
            }

            echo json_encode($this->feedbackToForm);
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
