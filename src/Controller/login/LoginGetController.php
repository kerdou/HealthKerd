<?php

namespace HealthKerd\Controller\login;

class LoginGetController extends LoginCommonController
{
    public function __destruct()
    {
    }


    /** recoit GET['action'] et lance la suite
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     * @return void
     */
    public function actionReceiver(array $cleanedUpGet): void
    {
        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'loginPage': // affichage de la page de login
                    $this->displayLoginPage();
                    break;

                case 'killSession': // coupure de session et renvoi vers la page de login
                    session_destroy();
                    echo "<script>window.location = 'index.php';</script>";
                    break;

                default: // si GET['action'] ne correspond à aucun cas de figure, on repart vers la page de login
                    $this->displayLoginPage();
            }
        } else {
            // si GET['action'] n'est pas défini, on repart vers la page de login
            $this->displayLoginPage();
        }
    }
}
