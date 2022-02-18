<?php

namespace HealthKerd\Model\sqlStmtStore\docSpemedicRelation;

/** Classe dédiée à la table doc_spemedic_relation
 * * Sujet: Fait le lien entre les pro de santé et leur spécialités médicales
 */
class SelectDocSpemedicRelation
{
    public function __destruct()
    {
    }

    /** Récupération des spécialités médicales d'un docteur
     * @param string $whereString       Colonne et clé à rechercher dans le WHERE
     * @return string                   Requête SQL complête
    */
    public function stmtDocSpeMedicRelation(string $whereString): string
    {
        $stmtStart =
            'SELECT
                doc_spemedic_relation.*,
                spe_medic_full_list.name
            FROM
                doc_list
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE';

        $stmtEnd =
            ' ORDER BY spe_medic_full_list.name;';

        return $stmtStart . $whereString . $stmtEnd;
    }

    /** Récupération des spécialités médicales d'un docteur
     * ------
     * Requête préparée
     * @return string                   Requête SQL complête
    */
    public function selectPreparedDocSpemedicRelationStmt(): string
    {
        $stmt =
            "SELECT
                doc_spemedic_relation.*,
                spe_medic_full_list.name
            FROM
                doc_spemedic_relation
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE docID = :docID
            ORDER BY spe_medic_full_list.name;";

        return $stmt;
    }

    /** Récupération des spécialités médicales utilisées par un user
     * @param string $whereString       Colonne et clé à rechercher dans le WHERE
     * @return string                   Requête SQL complête
    */
    public function stmtSpeMedicUsedByUser(string $whereString): string
    {
        $stmtStart =
            'SELECT DISTINCT
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name as speMedicName
            FROM
                doc_list
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE';

        $stmtEnd =
            ' ORDER BY spe_medic_full_list.name;';

        return $stmtStart . $whereString . $stmtEnd;
    }
}
