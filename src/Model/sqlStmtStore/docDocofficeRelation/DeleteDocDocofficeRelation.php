<?php

namespace HealthKerd\Model\sqlStmtStore\docDocofficeRelation;

/** Classe dédiée à la table doc_docoffice_relation
 * * Sujet: Relation entre les docteurs et leurs cabinet médicaux
 */
class DeleteDocDocofficeRelation
{
    public function __destruct()
    {
    }

    /** Suppression d'une relation entre un doc et un cabinet de santé
     * ----
     * * Requête préparée
     * @return string       Déclaration SQL complete
     */
    public function deletePrepDocDocofficeRelationStmt(): string
    {
        $stmt =
            "DELETE FROM
                doc_docoffice_relation
            WHERE
                docID = :docID
            AND
                docOfficeID = 1;";

        return $stmt;
    }
}
