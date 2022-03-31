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

        if (isset($cleanedUpGet['action'])) {
            switch ($cleanedUpGet['action']) {
                case 'showAccountPage':
                    $userSelectModel = new \HealthKerd\Model\modelInit\userAccount\UserSelectModel();
                    $userAccountData = $userSelectModel->gatherAllUserAccountData();
                    $view = new \HealthKerd\View\userAccount\userPage\UserAccountPageBuilder();
                    $view->buildOrder($userAccountData);
                    break;

                case 'showCreatForm':
                    break;

                case 'showEditForm':
                    $userSelectModel = new \HealthKerd\Model\modelInit\userAccount\UserSelectModel();
                    $userAccountData = $userSelectModel->gatherAllUserAccountData();
                    $view = new \HealthKerd\View\userAccount\userForm\EditFormBuilder();
                    $view->buildOrder($userAccountData);
                    break;

                case 'showPwdEditForm':
                    $view = new \HealthKerd\View\userAccount\userForm\EditPwdFormBuilder();
                    $view->buildOrder();
                    break;

                case 'showDelForm':
                    $userSelectModel = new \HealthKerd\Model\modelInit\userAccount\UserSelectModel();
                    $userAccountData = $userSelectModel->gatherAllUserAccountData();
                    $view = new \HealthKerd\View\userAccount\userForm\DelFormBuilder();
                    $view->buildOrder($userAccountData);
                    break;

                default:
                    echo "<script>window.location = 'index.php';</script>";
            }
        } else {
            echo "<script>window.location = 'index.php';</script>";
        }
    }
}
