<?php

namespace HealthKerd\Model\sqlStmtStore\medicEventCategory;

/** Classe dédiée à la table doc_list
 * * Sujet: Liste des professionnels de santé
 */
class SelectMedicEventCategory
{
    public function __destruct()
    {
    }

    /** Récuperer les données de toutes les catégories d'events utilisées par un user
     * -----
     * * Requête préparée
     * @return string       Requête SQL complête
     */
    public function gatherAllEventsCatsStmt(): string
    {
        $stmt =
            "SELECT
                *
            FROM
                medic_event_category
            WHERE
                userID = :userID
            ORDER BY
                'name';";

        return $stmt;
    }
}
