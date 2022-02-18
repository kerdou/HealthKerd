<?php

namespace HealthKerd\Model\sqlStmtStore\docList;

/** Classe dédiée à la table doc_list
 * * Sujet: Liste des professionnels de santé
 */
class InsertDocList
{
    public function __destruct()
    {
    }

    public function addDocStmt(): string
    {
        $stmt =
            "INSERT INTO doc_list
                VALUES (
                    NULL,
                    :userID,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    :title,
                    :lastName,
                    :firstName,
                    :tel,
                    :mail,
                    :webPage,
                    :doctolibPage,
                    :comment,
                    CURRENT_DATE()
                );";

        return $stmt;
    }
}
