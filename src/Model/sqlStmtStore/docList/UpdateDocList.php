<?php

namespace HealthKerd\Model\sqlStmtStore\docList;

/** Classe dédiée à la table doc_list
 * * Sujet: Liste des professionnels de santé
 */
class UpdateDocList
{
    public function __destruct()
    {
    }

    /** Modification d'un docteur
     * ----
     * * Requête préparée
     * @return string       Déclaration SQL complête
     */
    public function docEditStmt(): string
    {
        $stmt =
            "UPDATE
                doc_list
            SET
                title = :title,
                lastName = :lastName,
                firstName = :firstName,
                tel = :tel,
                mail = :mail,
                webPage = :webPage,
                doctolibPage = :doctolibPage,
                comment = :comment
            WHERE
                docID = :docID
            AND
                userID = :userID
            ;";

        return $stmt;
    }
}
