<?php

namespace HealthKerd\Controller\userAccount;

/** Contrôleur GET de gestion de compte user
 */
class UserAccountPostController
{
    private array $cleanedUpGet;
    private array $cleanedUpPost;

    private array $userFieldsToCheck = array();
    private array $userFeedbackToForm = array();

    private array $pwdFieldsToCheck = array();
    private array $pwdFeedbackToForm = array();

    public function __destruct()
    {
    }


    /** Recoit POST['action'] et lance la suite
     * @param array $cleanedUpGet       Données nettoyées du GET
     * @param array $cleanedUpPost      Données nettoyées du POST
     */
    public function actionReceiver(array $cleanedUpGet, array $cleanedUpPost): void
    {
        $this->cleanedUpGet = $cleanedUpGet;
        $this->cleanedUpPost = $cleanedUpPost;


        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'accountCreate':
                    // todo
                    $this->accountArraysSetup();
                    break;

                case 'accountModif':
                    $this->accountArraysSetup();
                    $this->accountModif();
                    break;

                case 'pwdModif':
                    $this->pwdArraySetup();
                    $this->pwdModif();
                    break;

                case 'accountDel':
                    break;

                default:
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            echo "<script>window.location = 'index.php';</script>";
        }
    }


    /** Préparation des arrays dédiés à la création ou modif de compte user
    */
    private function accountArraysSetup()
    {
        $this->userFieldsToCheck = array(
            'lastname' => $this->cleanedUpPost['lastname'],
            'firstname' => $this->cleanedUpPost['firstname'],
            'birthDate' => $this->cleanedUpPost['birthDate'],
            'login' => $this->cleanedUpPost['login'],
            'mail' => $this->cleanedUpPost['mail']
        );

        // ensemble des données à renvoyer au form, doit être completé un peu plus bas
        $this->userFeedbackToForm = array(
            'checkedInputs' => array(),
            'formIsValid' => false,
            'feedbackFromDB' => 'aborted',
            'newUserID' => -1
        );
    }


    /** Préparation des arrays dédiés à la création ou modif de mot de passe
     */
    private function pwdArraySetup()
    {
        $this->pwdFieldsToCheck = array(
            'pwd' => $this->cleanedUpPost['pwd'],
            'confPwd' => $this->cleanedUpPost['confPwd']
        );

        // ensemble des données à renvoyer au form, doit être completé un peu plus bas
        $this->pwdFeedbackToForm = array(
            'checkedInputs' => array(),
            'pwdsAreIdentical' => false,
            'pwdsAreValid' => false,
            'feedbackFromDB' => 'aborted'
        );
    }


    /** Modification d'un compte user
     */
    private function accountModif(): void
    {
        // vérification des données contenues dans le POST
        $userFormChecker = new \HealthKerd\Controller\userAccount\UserFormChecker();
        $userFormChecksFeedback = $userFormChecker->userFormChecks($this->userFieldsToCheck);
        $overallVerdictsArr = array(); // recoit les verdicts finaux des tests

        // extraction des verdicts finaux des tests dans $overallVerdictsArr
        // et remplissage des checkedInputs dans $feedbackToForm
        foreach ($userFormChecksFeedback as $field => $element) {
            array_push($overallVerdictsArr, $element['overallValidityVerdict']);

            $this->userFeedbackToForm['checkedInputs'][$field]['checksVerdicts'] = $element['checksVerdicts'];
            $this->userFeedbackToForm['checkedInputs'][$field]['overallValidityVerdict'] = $element['overallValidityVerdict'];
        }

        // s'il y a le moindre souci dans les checks on renvoie le résultat des tests au form, sinon on poursuit
        if (in_array(false, $overallVerdictsArr)) {
            echo json_encode($this->userFeedbackToForm);
        } else {
            $this->userFeedbackToForm['formIsValid'] = true;

            // formatage de la date de naissance avant insertion dans la DB
            $exploDate = explode('/', $this->cleanedUpPost['birthDate']);
            $exploDate['day'] = $this->zerosAdder($exploDate[0]);
            $exploDate['month'] = $this->zerosAdder($exploDate[1]);
            $exploDate['year'] = $exploDate[2];
            $this->cleanedUpPost['birthDate'] = $exploDate['year'] . '-' . $exploDate['month'] . '-' . $exploDate['day'];

            $userUpdateModel = new \HealthKerd\Model\modelInit\userAccount\UserUpdateModel();
            $pdoErrorMessage = $userUpdateModel->updateUserAccountData($this->cleanedUpPost);

            if (strlen($pdoErrorMessage) == 0) {
                $this->userFeedbackToForm['feedbackFromDB'] = 'success';

                $_SESSION['firstName'] = $this->cleanedUpPost['firstname'];
                $_SESSION['lastName'] = $this->cleanedUpPost['lastname'];
            } else {
                $this->userFeedbackToForm['feedbackFromDB'] = 'fail';
            }

            echo json_encode($this->userFeedbackToForm);
        }
    }


    /** Ajout d'un zéro pour renvoyer systématiquement un nombre à 2 chiffres
     */
    private function zerosAdder(int $nbr): string
    {
        $nbr = intval($nbr);
        $nbr = strval($nbr);

        if (strlen($nbr) == 1) {
            $nbr = '0' . $nbr;
        }

        return $nbr;
    }


    /** Modification du mot de passe
     * Gestion asynchrone
     */
    private function pwdModif(): void
    {
        $pwdFormChecker = new \HealthKerd\Controller\userAccount\PwdFormChecker();
        $this->pwdFeedbackToForm['checkedInputs'] = $pwdFormChecker->pwdFormChecks($this->pwdFieldsToCheck);

        $fieldsValidity = array();

        foreach ($this->pwdFeedbackToForm['checkedInputs'] as $key => $value) {
            array_push($fieldsValidity, $this->pwdFeedbackToForm['checkedInputs'][$key]['overallValidityVerdict']);
        }

        $this->pwdFeedbackToForm['pwdsAreValid'] = (in_array(false, $fieldsValidity)) ? false : true;
        $this->pwdFeedbackToForm['pwdsAreIdentical'] = ($this->pwdFieldsToCheck['pwd'] == $this->pwdFieldsToCheck['confPwd']) ? true : false;

        if ($this->pwdFeedbackToForm['pwdsAreValid'] && $this->pwdFeedbackToForm['pwdsAreIdentical']) {
            $hash = password_hash($this->cleanedUpPost['pwd'], PASSWORD_ARGON2ID);
            $userUpdateModel = new \HealthKerd\Model\modelInit\userAccount\UserUpdateModel();
            $pdoErrorMessage = $userUpdateModel->updateUserPwd($hash);

            if (strlen($pdoErrorMessage) == 0) {
                $this->pwdFeedbackToForm['feedbackFromDB'] = 'success';
            } else {
                $this->pwdFeedbackToForm['feedbackFromDB'] = 'fail';
            }
        }

        echo json_encode($this->pwdFeedbackToForm);
    }
}
