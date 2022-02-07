<?php

namespace HealthKerd\Controller\medic\docOffice;

/** Contrôleur POST des cabinets médicaux
 */
class DocOfficePostController extends DocOfficeCommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** recoit GET['action'] et lance la suite
     * @param array $cleanedUpGet   Infos nettoyées provenants du GET
     * @return void
     */
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
