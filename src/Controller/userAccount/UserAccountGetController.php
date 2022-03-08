<?php

namespace HealthKerd\Controller\userAccount;

/** Contrôleur GET de gestion de compte user
 */
class UserAccountGetController
{
    public function __destruct()
    {
    }

    /** Recoit GET['action'] et lance la suite
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     * @return void
     */
    public function actionReceiver(array $cleanedUpGet): void
    {
        echo '<h1>PLOP ACCOUNT GET CONTROLLER</h1>';
        var_dump($cleanedUpGet);

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'creationForm':
                    echo '<h1>CREATION FORM DISPLAY!!!!!!!!!!</h1>';
                    break;
                default:
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            echo "<script>window.location = 'index.php';</script>";
        }
    }
}
