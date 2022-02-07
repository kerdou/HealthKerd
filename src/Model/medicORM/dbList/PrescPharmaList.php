<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table treat_pharma_list
 * * Sujet: Liste des prescriptions des ordonnances pharmaceutiques
 */
class PrescPharmaList
{
    /** Génére la déclaration SQL des prescriptions des ordonnances de prélèvements pharmaceutiques pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT
                presc_pharma_list.prescPharmaID,
                treat_pharma_list.treatPharmaID,
                treat_pharma_list.name AS treatPharmaName,
                treat_pharma_role_on_affect.treatPharmaRoleID,
                treat_pharma_role_on_affect.name AS roleName,
                medic_affect_list.medicAffectID,
                medic_affect_list.name AS affectName,
                presc_pharma_list.content,
                ordo_pharma_list.ordoPharmaID,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_pharma_list ON diag_list.diagID = ordo_pharma_list.diagID
            INNER JOIN presc_pharma_list ON ordo_pharma_list.ordoPharmaID = presc_pharma_list.ordoPharmaID
            INNER JOIN treat_pharma_list ON presc_pharma_list.treatPharmaID = treat_pharma_list.treatPharmaID
            INNER JOIN treat_pharma_role_on_affect ON presc_pharma_list.treatPharmaRoleID = treat_pharma_role_on_affect.treatPharmaRoleID
            INNER JOIN medic_affect_list ON treat_pharma_role_on_affect.medicAffectID = medic_affect_list.medicAffectID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
