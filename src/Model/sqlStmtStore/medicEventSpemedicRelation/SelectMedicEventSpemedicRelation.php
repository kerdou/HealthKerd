<?php

namespace HealthKerd\Model\sqlStmtStore\medicEventSpemedicRelation;

/** Classe dédiée à la table medic_event_spemedic_relation
 * * Sujet: Fait le lien entre les medic events et les spécialités médicales concernées
 */
class SelectMedicEventSpemedicRelation
{
    public function __destruct()
    {
    }

    /** Génére la déclaration SQL pour les spécialités médicales rattachées aux events, pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmtStart =
            'SELECT
                medic_event_spemedic_relation.*,
                spe_medic_full_list.name
            FROM
                medic_event_list
            INNER JOIN medic_event_spemedic_relation ON medic_event_list.medicEventID =  medic_event_spemedic_relation.medicEventID
            INNER JOIN spe_medic_full_list ON medic_event_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        $stmtEnd = 'ORDER BY spe_medic_full_list.name;';

        return $stmtStart . $whereString . $stmtEnd;
    }
}
