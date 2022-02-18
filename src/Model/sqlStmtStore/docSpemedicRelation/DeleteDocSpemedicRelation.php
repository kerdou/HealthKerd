<?php

namespace HealthKerd\Model\sqlStmtStore\docSpemedicRelation;

/** Classe dédiée à la table doc_spemedic_relation
 * * Sujet: Fait le lien entre les pro de santé et leur spécialités médicales
 */
class DeleteDocSpemedicRelation
{
    public function __destruct()
    {
    }

    /** Suppression d'une relation entre un docteur et une spé médicale
     * ----
     * * Requête préparée
     * @return string       Déclaration SQL complete
     */
    public function deletePrepDocSpeMedicRelationStmt(): string
    {
        $stmt =
            "DELETE FROM
                doc_spemedic_relation
            WHERE
                docID = :docID
            AND
                speMedicID = 48;";

        return $stmt;
    }
}
