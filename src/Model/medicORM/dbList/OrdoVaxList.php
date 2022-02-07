<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table ordo_vax_list
 * * Sujet: Liste des ordonnances vaccinales
 */
class OrdoVaxList
{
    /** Génére la déclaration SQL des ordonnances vaccinales pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmtStart = '
            SELECT
                ordo_vax_list.ordoVaxID,
                ordo_vax_list.date,
                ordo_vax_list.comment,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_vax_list ON diag_list.diagID = ordo_vax_list.diagID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        $stmtEnd = 'ORDER BY ordo_vax_list.date;';

        /*
            SELECT
                ordo_vax_list.ordoVaxID,
                ordo_vax_list.date,
                ordo_vax_list.comment,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_vax_list ON diag_list.diagID = ordo_vax_list.diagID
            WHERE
                medic_event_list.userID = 1
            ORDER BY
                ordo_vax_list.date
            ;'
        */



        return $stmtStart . $whereString . $stmtEnd;
    }
}