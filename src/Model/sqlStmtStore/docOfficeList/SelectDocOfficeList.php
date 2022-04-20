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
                doc_office_list.docOfficeID,
                doc_office_list.name,
                doc_office_list.cityName
            FROM
                doc_office_list
            WHERE
                userID = :userID
            ORDER BY
                doc_office_list.cityName,
                doc_office_list.name;";

        return $stmt;
    }

    /** Récuperer les données basiques de tous les cabinets médicaux consultés par un user
     * @return string           Déclaration SQL compléte
     */
    public function selectEveryDocOfficesOfUserStmt(): string
    {
        $stmtStart =
            'SELECT
                doc_office_list.docOfficeID,
                doc_office_list.name,
                doc_office_list.cityName
            FROM doc_office_list
            WHERE doc_office_list.userID = ';

        $stmtEnd =
            ' ORDER BY
                doc_office_list.cityName,
                doc_office_list.name;';

        return $stmtStart . $_SESSION['userID'] . $stmtEnd;
    }

    /** Récupération de tous les doc offices d'un doc
     * @param string $docID    ID du doc
     * @return string           Déclaration SQL compléte
     */
    public function selectDocOfficesOfDocStmt(string $docID): string
    {
        $stmtStart =
            'SELECT
                doc_office_list.docOfficeID
            FROM doc_office_list
            INNER JOIN doc_docoffice_relation ON doc_office_list.docOfficeID = doc_docoffice_relation.docOfficeID
            WHERE doc_docoffice_relation.docID = ';

        $stmtEnd =
            ' ORDER BY
                doc_office_list.cityName,
                doc_office_list.name;';

        return $stmtStart . $docID . $stmtEnd;
    }
}
