<?php

namespace HealthKerd\Controller\medic\doc;

/** Controleur de la section 'accueil' */
class DocPostController extends DocCommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function actionReceiver(array $cleanedUpGet)
    {
        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'todo':
                    //$this->displayLoginPage();
                    break;
                default:
                    //echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            //echo "<script>window.location = 'index.php';</script>";
        }
    }
}
