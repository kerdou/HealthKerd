<?php

namespace HealthKerd\Model\sqlStmtStore\docDocofficeRelation;

/** Classe dédiée à la table doc_docoffice_relation
 * * Sujet: Relation entre les docteurs et leurs cabinet médicaux
 */
class SelectDocDocofficeRelation
{
    public function __destruct()
    {
    }


    /** Récuperer les données basiques des cabinets médicaux par rapport aux docteurs qui y sont liés
     * -----
     * * Requête préparée
     * @return string       Déclaration SQL compléte
     */
    public function gatherAllDocOfficesBasicsStmt(): string
    {
        $stmt =
            'SELECT
                doc_docoffice_relation.*,
                doc_office_list.name,
                doc_office_list.cityName
            FROM
                doc_docoffice_relation
            INNER JOIN doc_office_list ON doc_docoffice_relation.docOfficeID = doc_office_list.docOfficeID
            WHERE docID = :docID;';

        return $stmt;
    }
}
