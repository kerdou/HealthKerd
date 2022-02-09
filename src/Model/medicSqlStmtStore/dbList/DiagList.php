<?php

namespace HealthKerd\Model\medicSqlStmtStore\dbList;

/** Classe dédiée à la table diag_list
 * * Sujet: Liste des diagnostics rattachés aux events
 */
class DiagList
{
    /** Génére la déclaration SQL pour les diagnostics des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmt = '
            SELECT
                diag_list.diagID,
                diag_list.comment,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
