<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table diag_check_points
 * * Sujet: Points de contrôles faits pendant les diagnostics
 */
class DiagCheckPoints
{
    /** Génére la déclaration SQL pour les points de contrôle de diag des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT
                diag_check_points.diagCheckPointID,
                diag_check_points.checkpoint,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN diag_check_points ON diag_list.diagID = diag_check_points.diagID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
