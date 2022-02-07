<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table diag_symptoms
 * * Sujet: Liste des symptômes déclarés lors les diagnostics
 */
class DiagSymptoms
{
    /** Génére la déclaration SQL pour les symptômes de diag des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT DISTINCT
                diag_symptoms.diagSymptomID,
                diag_symptoms.symptom,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN diag_symptoms ON diag_list.diagID = diag_symptoms.diagID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
