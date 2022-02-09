<?php

namespace HealthKerd\Model\medicSqlStmtStore\dbList;

/** Classe dédiée à la table presc_vax_list
 * * Sujet: Liste des prescriptions des ordonnances vaccinales
 */
class PrescVaxList
{
    /** Génére la déclaration SQL des prescriptions des ordonnances vaccinales pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmt = '
            SELECT
                presc_vax_list.*,
                ordo_vax_list.ordoVaxID,
                treat_pharma_list.name,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_vax_list ON diag_list.diagID = ordo_vax_list.diagID
            INNER JOIN presc_vax_list ON ordo_vax_list.ordoVaxID = presc_vax_list.ordoVaxID
            INNER JOIN treat_pharma_list ON presc_vax_list.treatPharmaID = treat_pharma_list.treatPharmaID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        /*
            SELECT
                presc_vax_list.*,
                ordo_vax_list.ordoVaxID,
                treat_pharma_list.name,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_vax_list ON diag_list.diagID = ordo_vax_list.diagID
            INNER JOIN presc_vax_list ON ordo_vax_list.ordoVaxID = presc_vax_list.ordoVaxID
            INNER JOIN treat_pharma_list ON presc_vax_list.treatPharmaID = treat_pharma_list.treatPharmaID
            WHERE
                medic_event_list.userID = 1
            ;
        */

        return $stmt . $whereString . ';';
    }
}
