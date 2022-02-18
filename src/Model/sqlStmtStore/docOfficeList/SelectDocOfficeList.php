<?php

namespace HealthKerd\Model\sqlStmtStore\docOfficeList;

/** Classe dédiée à la table doc_office_list
 * * Sujet: Liste des cabinets de santé
 */
class SelectDocOfficeList
{
    public function __destruct()
    {
    }


    /** Récuperer les données basiques de tous les cabinets médicaux consultés par un user
     * -----
     * * Requête préparée
     * @return string       Déclaration SQL compléte
     */
    public function gatherAllDocOffices(): string
    {
        $stmt =
            "SELECT
                docOfficeID, name, cityName
            FROM
                doc_office_list
            WHERE
                userID = :userID
            ORDER BY
                name;";

        return $stmt;
    }
}
