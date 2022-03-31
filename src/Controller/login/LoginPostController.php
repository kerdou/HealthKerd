<?php

namespace HealthKerd\Controller\login;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class LoginPostController extends LoginCommonController
{
    private object $loginModel; // Récupére les données des 3 derniers clients et des 3 derniers prospects

    public function __construct()
    {
        $this->loginModel = new \HealthKerd\Model\modelInit\login\LoginSelectModel();
    }

    public function __destruct()
    {
    }

    /** recoit POST['action'] et lance la suite
     * @param array $cleanedUpPost   Infos nettoyées provenants du POST
     * @return void
     */
    public function actionReceiver(array $cleanedUpPost): void
    {
        if (isset($cleanedUpPost['action'])) {
            switch ($cleanedUpPost['action']) {
                case 'logMeIn': // tentative de connexion d'un user
                    $userData = $this->loginModel->checkUserLogs($cleanedUpPost);

                    if (!empty($userData)) { // en cas de succés, on part vers la homepage
                        $pwdMatch = password_verify($cleanedUpPost['userPwd'], $userData['userPwd']);

                        if ($pwdMatch) {
                            $this->acceptedLogin($userData);
                        } else {
                            echo "<script>window.location = 'index.php';</script>";
                        }

                    } else { // en cas d'échec, on retourne à la page de login
                        echo "<script>window.location = 'index.php';</script>";
                    }

                    break;

                default: // si $cleanedUpPost['action'] ne correspond à aucun cas de figure, on repart vers le controlleur maître
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            // si $cleanedUpPost['action'] n'est pas défini, on repart vers le controlleur maître
            echo "<script>window.location = 'index.php';</script>";
        }
    }

    /**
     *
     */
    private function acceptedLogin(array $userData): void {
            $_SESSION['userID'] = intval($userData['userID']);
            $_SESSION['isAdmin'] = intval($userData['isAdmin']);
            $_SESSION['firstName'] = $userData['firstName'];
            $_SESSION['lastName'] = $userData['lastName'];
            echo "<script>window.location = 'index.php?controller=home';</script>";
    }
}
