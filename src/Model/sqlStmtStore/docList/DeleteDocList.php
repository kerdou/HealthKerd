<?php

namespace HealthKerd\Model\sqlStmtStore\docList;

/** Classe dédiée à la table doc_list
 * * Sujet: Liste des professionnels de santé
 */
class DeleteDocList
{
    public function __destruct()
    {
    }

    /** Suppression d'un docteur
     * ----
     * * Requête préparée
     */
    public function deleteDocStmt()
    {
        $stmt =
            "DELETE FROM
                doc_list
            WHERE
                docID = :docID
            AND
                userID = :userID
            ";

        return $stmt;
    }
}
