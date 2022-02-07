<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table vax_sessions_side_effects
 * * Sujet: Liste des effets secondaires des sessions de vaccination
 */
class VaxSessionsSideEffects
{
    /** Génére la déclaration SQL pour les éléments des sessions de soin des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT
                vax_sessions_side_effects.*,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN vax_sessions_list ON medic_event_list.medicEventID = vax_sessions_list.medicEventID
            INNER JOIN vax_sessions_side_effects ON vax_sessions_list.vaxSessionID = vax_sessions_side_effects.vaxSessionID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
