<?php

namespace HealthKerd\Model\sqlStmtStore\usersList;

/** Classe dédiée à la table doc_list
 * * Sujet: Liste des professionnels de santé
 */
class SelectUsersList
{
    public function __destruct()
    {
    }

    /** Récupération des identifiants dans la base selon le userLogin envoyé par le user
     * @return string       Requête SQL complête
     */
    public function checkUserLogsStmt(): string
    {
        $stmt =
            'SELECT
                userID, userPwd, isAdmin, firstName, lastName
            FROM
                users_list
            WHERE
                userLogin = :userLogin';

        return $stmt;
    }
}
