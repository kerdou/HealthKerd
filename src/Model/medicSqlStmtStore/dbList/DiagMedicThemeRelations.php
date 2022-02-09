<?php

namespace HealthKerd\Model\medicSqlStmtStore\dbList;

/** Classe dédiée à la table diag_medic_themes_relation
 * * Sujet: Liste des thèmes médicaux rattachés aux diagnostics
 */
class DiagMedicThemeRelations
{
    /** Génére la déclaration SQL pour les thèmes médicaux des diagnostics rattachés aux events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmt = '
            SELECT DISTINCT
                medic_theme_list.medicThemeID,
                medic_theme_list.name AS medicThemeName,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN diag_check_points ON diag_list.diagID = diag_check_points.diagID
            INNER JOIN diag_medic_themes_relation ON diag_list.diagID = diag_medic_themes_relation.diagID
            INNER JOIN medic_theme_list ON diag_medic_themes_relation.medicThemeID = medic_theme_list.medicThemeID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }
}
