<?php

namespace HealthKerd\Controller\userAccount;

class UserAccountPostController
{
    private object $homeModel; // Récupére les données des 3 derniers clients et des 3 derniers prospects
    private object $homeView; // Affichage des tableaux de la section accueil


    public function __destruct()
    {
    }

    /** Récupére [$_POST['action']] et lance l'affichage de la page voulue */
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
