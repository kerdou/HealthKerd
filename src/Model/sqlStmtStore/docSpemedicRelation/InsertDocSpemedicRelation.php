<?php

namespace HealthKerd\Model\sqlStmtStore\docSpemedicRelation;

/** Classe dédiée à la table doc_spemedic_relation
 * * Sujet: Fait le lien entre les pro de santé et leur spécialités médicales
 */
class InsertDocSpemedicRelation
{
    public function __destruct()
    {
    }

    /** Ajout d'une médicale à un docteur
     * TODO: Modifier pour ne pas assigner une spé médicale par défaut
     * ----
     * * Requête préparée
     * @return string       Déclaration SQL complête
     */
    public function addDocSpemedicRelationStmt(): string
    {
        $stmt =
            "INSERT INTO
                doc_spemedic_relation
            VALUES (:docID, 48);";

        return $stmt;
    }
}
