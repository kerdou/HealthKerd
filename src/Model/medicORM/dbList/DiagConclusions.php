<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dediée à la table diag_conclusions
 * * Sujet: Conclusions des diagnostics d'events
 */
class DiagConclusions
{
    /** Génére la déclaration SQL pour les conclusions des diag d'events demandés
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT
                diag_conclusions.diagConclusionID,
                diag_conclusions.dateTime,
                diag_conclusions.sickDetailedStatus,
                medic_affect_list.medicAffectID,
                medic_affect_list.name AS medicAffectName,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN diag_conclusions ON diag_list.diagID = diag_conclusions.diagID
            INNER JOIN medic_affect_list ON diag_conclusions.medicAffectID = medic_affect_list.medicAffectID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
