<?php

namespace HealthKerd\Controller\login;

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
                            $this->writeLoginFailureToLogs('Wrong password', $cleanedUpPost['userLogin']);
                            echo "<script>window.location = 'index.php?givenUser=" . $cleanedUpPost['userLogin'] . "&wrongPassword=true';</script>";
                        }
                    } else { // si le compte n'existe pas
                        $this->writeLoginFailureToLogs('Account doesn\'t exist', $cleanedUpPost['userLogin']);
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
     * Ecrit les données dans $_SESSION pour être accessibles partout
     * @param array $userData   Données du user renvoyées par le DB
     */
    private function acceptedLogin(array $userData): void
    {
        $_SESSION['userID'] = intval($userData['userID']);
        $_SESSION['isAdmin'] = intval($userData['isAdmin']);
        $_SESSION['firstName'] = $userData['firstName'];
        $_SESSION['lastName'] = $userData['lastName'];
        echo "<script>window.location = 'index.php?controller=home';</script>";
    }

    /** Ajoute des lignes de logs dans /logs/failedConnection.log en cas de refus de login
     * Peut être utile à Fail2Ban
     * @param string $type      Description tu type d'erreur
     * @param string $login     Login entré par le user
     */
    private function writeLoginFailureToLogs(string $type, string $login): void
    {
        $dateObj = getdate($_ENV['DATEANDTIME']['nowDate']['nowTimestamp']);

        $logLine =
            $dateObj['mday'] . '-' . $dateObj['mon'] . '-' . $dateObj['year'] . ' ' .
            $dateObj['hours'] . ':' . $dateObj['minutes'] . ':' . $dateObj['seconds'] . ' /// ' .
            'Timestamp: ' . $_ENV['DATEANDTIME']['nowDate']['nowTimestamp'] . ' /// ' .
            'From: ' . $_SERVER['REMOTE_ADDR'] . ' /// ' .
            'Type: ' . $type . ' /// ' .
            'Login: ' . $login;

        file_put_contents($_ENV['APPROOTPATH'] . '/logs/rejectedLogins.log', $logLine . PHP_EOL, FILE_APPEND);
    }
}
