<?php

namespace HealthKerd\Controller\userAccount;

/** Contrôleur GET de gestion de compte user
 */
class UserAccountPostController
{
    private array $cleanedUpGet;
    private array $cleanedUpPost;

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

    /**
     *
     */
    private function accountModif(): void
    {
        // vérification des données contenues dans le POST
        $checksArray = array();
        $userFormChecker = new \HealthKerd\Controller\userAccount\UserFormChecker();
        $checksArray = $userFormChecker->userFormChecks($this->cleanedUpPost);

        // si $checksArray contient des erreurs (des false), on réaffiche le formulaire en indiquant les champs à modifier
        if (in_array(false, $checksArray)) {
            $userAccountView = new \HealthKerd\View\userAccount\userForm\FailedEditFormBuilder();
            $userAccountView->buildOrder($this->cleanedUpPost, $checksArray);
        } else {
            $exploDate = explode('/', $this->cleanedUpPost['birthDate']);
            $exploDate['day'] = $this->zerosAdder($exploDate[0]);
            $exploDate['month'] = $this->zerosAdder($exploDate[1]);
            $exploDate['year'] = $exploDate[2];
            $this->cleanedUpPost['birthDate'] = $exploDate['year'] . '-' . $exploDate['month'] . '-' . $exploDate['day'];

            $userUpdateModel = new \HealthKerd\Model\modelInit\userAccount\UserUpdateModel();
            $pdoErrorMessage = $userUpdateModel->updateUserAccountData($this->cleanedUpPost);

            if (strlen($pdoErrorMessage) == 0) {
                $_SESSION['firstName'] = $this->cleanedUpPost['firstname'];
                $_SESSION['lastName'] = $this->cleanedUpPost['lastname'];
            }

            echo "<script>window.location = 'index.php?controller=userAccount&action=showAccountPage';</script>";
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
