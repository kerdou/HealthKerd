<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table ordo_labo_list
 * * Sujet: Liste des ordonnances de prélèvements en laboratoire médical
 */
class OrdoLaboList
{
    /** Génére la déclaration SQL des ordonnances de prélèvements en laboratoire médical pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmtStart = '
            SELECT
                ordo_labo_list.ordoLaboID,
                ordo_labo_list.date,
                ordo_labo_list.renewal,
                ordo_labo_list.comment,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_labo_list ON diag_list.diagID = ordo_labo_list.diagID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        $stmtEnd = 'ORDER BY ordo_labo_list.date;';

        return $stmtStart . $whereString . $stmtEnd;
    }
}
