<?php

namespace HealthKerd\Model\sqlStmtStore\vaxSessionsList;

/** Classe dédiée à la table vax_sessions_list
 * * Sujet: Liste des sessions de vaccination
 */
class SelectVaxSessionsList
{
    public function __destruct()
    {
    }

    /** Génére la déclaration SQL pour les sessions de vaccination des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForDiagsOnEvent(string $whereString): string
    {
        $stmt =
            'SELECT
                vax_sessions_list.*,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN vax_sessions_list ON medic_event_list.medicEventID = vax_sessions_list.medicEventID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
