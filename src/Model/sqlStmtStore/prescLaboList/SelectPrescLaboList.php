<?php

namespace HealthKerd\Model\sqlStmtStore\prescLaboList;

/** Classe dédiée à la table presc_labo_list
 * * Sujet: Liste des prescriptions des ordonnances de prélèvements en laboratoire médical
 */
class SelectPrescLaboList
{
    public function __destruct()
    {
    }

    /** Génére la déclaration SQL des prescriptions des  ordonnances de prélèvements en laboratoire médical pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmt =
            'SELECT
                presc_labo_list.prescLaboID,
                presc_labo_list.samplingType,
                presc_labo_list.comment,
                ordo_labo_list.ordoLaboID,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_labo_list ON diag_list.diagID = ordo_labo_list.diagID
            INNER JOIN presc_labo_list ON ordo_labo_list.ordoLaboID = presc_labo_list.ordoLaboID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
