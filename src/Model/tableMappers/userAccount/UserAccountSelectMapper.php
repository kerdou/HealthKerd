<?php

namespace HealthKerd\Model\tableMappers\userAccount;

class UserAccountSelectMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Récupération de toutes les données du compte du user
     */
    public function gatherAllUserAccountDataMapper()
    {
        $this->maps['SelectUsersList'] = new \HealthKerd\Model\sqlStmtStore\usersList\SelectUsersList();
    }
}
