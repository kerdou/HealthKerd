<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table ordo_labo_slots
 * * Sujet: Liste des slots liés aux ordonnances de prélèvements en laboratoire médical
 */
class OrdoLaboSlots
{
    /** Génére la déclaration SQL des slots des ordonnances de prélèvements en laboratoire médical pour les diagnostics des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForDiagsOnEvent(string $whereString)
    {
        $stmt = '
            SELECT
                ordo_labo_slots.*,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_labo_list ON diag_list.diagID = ordo_labo_list.diagID
            INNER JOIN ordo_labo_slots ON ordo_labo_list.ordoLaboID = ordo_labo_slots.ordoLaboID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
