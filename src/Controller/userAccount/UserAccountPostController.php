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

        if ($this->cleanedUpGet['action'] == 'accountCreate' || $this->cleanedUpGet['action'] == 'accountModif') {
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


        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'accountCreate':
                    // todo
                    echo "<h1>CREATION DE COMPTE</h1>";
                    break;

                case 'accountModif':
                    $this->accountModif();
                    break;

                case 'pwdModif':
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

    /**
     *
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

    /**
     *
     */
    private function pwdModif(): void
    {
        $checksArray = array();
        $pwdFormChecker = new \HealthKerd\Controller\userAccount\PwdFormChecker();
        $checksArray = $pwdFormChecker->pwdFormChecks($this->cleanedUpPost);

        if ($checksArray['overall']['identical'] == true && $checksArray['overall']['areValid'] == true) {
            $hash = password_hash($this->cleanedUpPost['pwd'], PASSWORD_ARGON2ID);
            $userUpdateModel = new \HealthKerd\Model\modelInit\userAccount\UserUpdateModel();
            $pdoErrorMessage = $userUpdateModel->updateUserPwd($hash);
            echo "<script>window.location = 'index.php?controller=userAccount&action=showAccountPage';</script>";
        } else {
            $userAccountView = new \HealthKerd\View\userAccount\userForm\FailedEditPwdFormBuilder();
            $userAccountView->buildOrder($this->cleanedUpPost, $checksArray);
        }
    }
}
