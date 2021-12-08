<?php

namespace HealthKerd\Controller\medic\docOffice;

/** Controleur de la section 'accueil' */
class DocOfficePostController extends DocOfficeCommonController
{
    public function __construct()
    {
        parent::__construct();
    }


    public function __destruct()
    {
    }


    public function actionReceiver(array $cleanedUpGet)
    {
        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'todo':
                    //$this->displayLoginPage();
                    break;
                default:
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            echo "<script>window.location = 'index.php';</script>";
        }
    }
}
