<?php

namespace HealthKerd\Model\sqlStmtStore\docDocofficeRelation;

/** Classe dédiée à la table doc_docoffice_relation
 * * Sujet: Relation entre les docteurs et leurs cabinet médicaux
 */
class InsertDocDocofficeRelation
{
    public function __destruct()
    {
    }

    /** Ajout d'un cabinet médical à un docteur
     * TODO: Modifier pour ne pas assigner un cabinet par défaut
     * ----
     * * Requête préparée
     * @return string       Déclaration SQL complête
     */
    public function addDocDocofficeRelationStmt(): string
    {
        $stmt =
            "INSERT INTO
                doc_docoffice_relation
            VALUES (:docID, 1);";

        return $stmt;
    }
}
