<?php

namespace HealthKerd\Controller\userAccount;

/** Contrôleur GET de gestion de compte user
 */
class UserAccountPostController
{
    private object $homeModel; // Récupére les données des 3 derniers clients et des 3 derniers prospects
    private object $homeView; // Affichage des tableaux de la section accueil

    public function __destruct()
    {
    }

    /** Recoit POST['action'] et lance la suite
     * @param array $cleanedUpPost   Infos nettoyées provenants du POST
     * @return void
     */
    public function actionReceiver(array $cleanedUpPost)
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
