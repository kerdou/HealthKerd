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
     * Requête préparée
     * @return string       Requête SQL complête
     */
    public function checkUserLogsStmt(): string
    {
        $stmt =
            'SELECT userID, userPwd, isAdmin, firstName, lastName
            FROM users_list
            WHERE userLogin = :userLogin';

        return $stmt;
    }

    /** Récupération de toutes les données de compte d'un user en complément de celle déjà présentes dans [$_SESSION]
     * Requête préparée
     * @return string       Requête SQL complête
     */
    public function gatherAllUserAccountData(): string
    {
        $stmt =
            'SELECT
                userLogin,
                modifLocked,
                email,
                birthDate,
                gender,
                accountCreationDate
            FROM users_list
            WHERE userID = :userID';

        return $stmt;
    }
}
