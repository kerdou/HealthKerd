<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table care_session_elements
 * * Sujet: Liste des éléments des sessions de soin
 */
class CareSessionElements
{
    /** Génére la déclaration SQL pour les éléments des sessions de soin des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT
                care_session_elements.careSessionElementID,
                care_session_elements.name,
                care_sessions_list.careSessionID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN care_sessions_list ON medic_event_list.medicEventID = care_sessions_list.medicEventID
            INNER JOIN care_session_elements ON care_sessions_list.careSessionID = care_session_elements.careSessionID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
