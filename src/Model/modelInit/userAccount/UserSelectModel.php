<?php

namespace HealthKerd\Model\modelInit\userAccount;

class UserSelectModel extends \HealthKerd\Model\common\PdoBufferManager
{
    private object $mapper;

    public function __construct()
    {
        parent::__construct();
        $this->mapper = new \HealthKerd\Model\tableMappers\userAccount\UserAccountSelectMapper();
    }

    public function __destruct()
    {
    }

    /** Récupération des données de compte d'un user
     */
    public function gatherAllUserAccountData(): array
    {
        $this->mapper->gatherAllUserAccountDataMapper();

        $userStmt = $this->mapper->maps['SelectUsersList']->gatherAllUserAccountData();
        $userQuery = $this->pdo->prepare($userStmt);
        $userQuery->bindParam(':userID', $_SESSION['userID']);
        $userQuery->execute();
        $userResult = $userQuery->fetch(\PDO::FETCH_ASSOC);

        return $userResult;
    }
}
