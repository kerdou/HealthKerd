<?php

namespace HealthKerd\Model\tableMappers\login;

/** Stockage des templates de requêtes SQL dédiées au Select d'events médicaux
*/
class LoginSelectMapper
{
    public array $loginSelectMaps = array();

    public function __destruct()
    {
    }

    /** Vérification des idenfifiants d'un user
     */
    public function checkUserLogsMapping(): void
    {
        $this->loginSelectMaps['SelectUserList'] = new \HealthKerd\Model\sqlStmtStore\usersList\SelectUsersList();
    }
}
