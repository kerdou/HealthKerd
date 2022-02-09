<?php

namespace HealthKerd\Model\medicSqlStmtStore\dbList;

/** Classe dédiée à la table ordo_vax_slots
 * * Sujet: Liste des slots liés aux ordonnances vaccinales
 */
class OrdoVaxSlots
{
    /** Génére la déclaration SQL des slots des ordonnances vaccinales pour les diagnostics des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForDiagsOnEvent(string $whereString): string
    {
        $stmt = '
            SELECT
                ordo_vax_slots.*,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_vax_list ON diag_list.diagID = ordo_vax_list.diagID
            INNER JOIN ordo_vax_slots ON ordo_vax_list.ordoVaxID = ordo_vax_slots.ordoVaxID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        /*
            SELECT
                ordo_vax_slots.*,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_vax_list ON diag_list.diagID = ordo_vax_list.diagID
            INNER JOIN ordo_vax_slots ON ordo_vax_list.ordoVaxID = ordo_vax_slots.ordoVaxID
            WHERE
                medic_event_list.userID = 1;
        */

        return $stmt . $whereString . ';';
    }


    /** Génére la déclaration SQL des slots des ordonnances vaccinales pour les sessions de vaccination des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForVaxSessionOnEvent(string $whereString): string
    {
        $stmt = '
            SELECT
                ordo_vax_slots.*,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN vax_sessions_list ON medic_event_list.medicEventID = vax_sessions_list.medicEventID
            INNER JOIN ordo_vax_slots ON vax_sessions_list.vaxSessionID = ordo_vax_slots.vaxSessionID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }

}
