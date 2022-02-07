<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table care_sessions_list
 * * Sujet: Liste des sessions de soin
 */
class CareSessionsList
{
    /** Génére la déclaration SQL pour les sessions de soin des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT
                care_sessions_list.*,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN care_sessions_list ON medic_event_list.medicEventID = care_sessions_list.medicEventID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
