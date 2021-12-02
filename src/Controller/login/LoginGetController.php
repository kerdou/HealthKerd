<?php

namespace HealthKerd\Controller\login;

class LoginGetController extends LoginCommonController
{
    public function __destruct()
    {
    }

    /** Récupére [$_GET['action']] et lance l'affichage de la page voulue */
    public function actionReceiver(array $cleanedUpGet)
    {
        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'loginPage':
                    $this->displayLoginPage();
                    break;
                case 'killSession':
                    session_destroy();
                    echo "<script>window.location = 'index.php';</script>";
                    break;
                default:
                    $this->displayLoginPage();
            }
        } else {
            $this->displayLoginPage();
        }
    }
}
