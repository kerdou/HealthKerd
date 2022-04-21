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
    public function actionReceiver(array $cleanedUpGet, array $cleanedUpPost): void
    {
        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'logMeIn': // tentative de connexion d'un user
                    $userData = $this->loginModel->checkUserLogs($cleanedUpPost);

                    if ($userData['countResult'] == '1') { // si le compte existe
                        $pwdMatch = password_verify($cleanedUpPost['userPwd'], $userData['logsResult']['userPwd']);

                        if ($pwdMatch) { // si le mot de passe est correct
                            $this->acceptedLogin($userData['logsResult']);
                        } else { // si le mot de passe est incorrect
                            echo "<script>window.location = 'index.php?givenUser=" . $cleanedUpPost['userLogin'] . "&wrongPassword=true';</script>";
                        }
                    } else { // si le compte n'existe pas
                        echo "<script>window.location = 'index.php?givenUser=" . $cleanedUpPost['userLogin'] . "&unknownUser=true';</script>";
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

    /** Renvoie vers la homepage une fois la connexion acceptée
     */
    private function acceptedLogin(array $userData): void
    {
        $_SESSION['userID'] = intval($userData['userID']);
        $_SESSION['isAdmin'] = intval($userData['isAdmin']);
        $_SESSION['firstName'] = $userData['firstName'];
        $_SESSION['lastName'] = $userData['lastName'];
        echo "<script>window.location = 'index.php?controller=home';</script>";
    }
}
