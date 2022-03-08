<?php

namespace HealthKerd\Controller\userAccount;

/** Contrôleur GET de gestion de compte user
 */
class UserAccountPostController
{
    public function __destruct()
    {
    }

    /** Recoit POST['action'] et lance la suite
     * @param array $cleanedUpPost   Infos nettoyées provenants du POST
     * @return void
     */
    public function actionReceiver(array $cleanedUpPost): void
    {
        if (isset($cleanedUpPost['action'])) {
            switch ($cleanedUpPost['action']) {
                case 'accountCreate':
                    // todo
                    echo "<h1>CREATION DE COMPTE</h1>";
                    break;
                default:
                    echo '<h1>CREATION DE COMPTE DEFAULT!!!!</h1>';
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            echo "<script>window.location = 'index.php';</script>";
        }
    }
}
