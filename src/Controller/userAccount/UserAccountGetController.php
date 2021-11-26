<?php

namespace HealthKerd\Controller\userAccount;

class UserAccountGetController
{
    private object $homeModel; // Récupére les données des 3 derniers clients et des 3 derniers prospects
    private object $homeView; // Affichage des tableaux de la section accueil

    /** Récupére [$_GET['action']] et lance l'affichage de la page voulue */
    public function actionReceiver(array $cleanedUpGet)
    {
        echo '<h1>PLOP ACCOUNT GET CONTROLLER</h1>';
        var_dump($cleanedUpGet);

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'creationForm':
                    echo '<h1>CREATION FORM DISPLAY!!!!!!!!!!</h1>';
                    break;
                default:
                    //echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            //echo "<script>window.location = 'index.php';</script>";
        }
    }
}
