<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table medic_event_affects_relation
 * * Sujet: Fait le lien entre les events médicaux et les maladies concernées
 */
class MedicEventAffectsRelation
{
    /** Génére la déclaration SQL pour indiquer les maladie, leurs ID et les events auxquelles elles sont rattachées, pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmtStart = '
            SELECT
                medic_event_affects_relation.*,
                medic_affect_list.name
            FROM
                medic_event_list
            INNER JOIN medic_event_affects_relation ON medic_event_list.medicEventID = medic_event_affects_relation.medicEventID
            INNER JOIN medic_affect_list ON medic_event_affects_relation.medicAffectID = medic_affect_list.medicAffectID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        $stmtEnd = 'ORDER BY medic_affect_list.name;';

        //return $stmtStart . $whereString . ';';
        return $stmtStart . $whereString . $stmtEnd;
    }
}
