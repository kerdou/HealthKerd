<?php

namespace HealthKerd\Model\sqlStmtStore\usersList;

/** Classe dédiée à la table doc_list
 * * Sujet: Liste des comptes utilisateurs
 */
class UpdateUsersList
{
    public function __destruct()
    {
    }

    /** Modification du compte user
     * Requête préparée
     * @return string       Requête SQL complête
     */
    public function updateUserDataStmt(): string
    {
        $stmt =
            "UPDATE
                users_list
            SET
                userLogin = :userLogin,
                email = :email,
                firstName = :firstName,
                lastName = :lastName,
                birthDate = :birthDate,
                gender = :gender
            WHERE
                userID = :userID;";

        return $stmt;
    }

    /** Modification du mot de passe du user
     * @return string       Requête SQL complête
     */
    public function updateUserPwdStmt(): string
    {
        $stmt =
            "UPDATE users_list
            SET userPwd = :userPwd
            WHERE userID = :userID;";

        return $stmt;
    }
}
