<?php

namespace HealthKerd\Model\tableMappers\userAccount;

class UserAccountUpdateMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Modification des données du compte du user
     */
    public function updateUserAccountDataMapper(): void
    {
        $this->maps['UpdateUsersList'] = new \HealthKerd\Model\sqlStmtStore\usersList\UpdateUsersList();
    }

    /** Modification des données du compte du user
     */
    public function updateUserPwdMapper(): void
    {
        $this->maps['UpdateUsersList'] = new \HealthKerd\Model\sqlStmtStore\usersList\UpdateUsersList();
    }
}
