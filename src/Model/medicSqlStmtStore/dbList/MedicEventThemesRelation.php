<?php

namespace HealthKerd\Model\medicSqlStmtStore\dbList;

/** Classe dédiée à la table medic_event_themes_relation
 * * Sujet: Liste les relations entre les medic event et les thèmes médicaux qui y sont rattachés
 */
class MedicEventThemesRelation
{
    /** Génére la déclaration SQL pour les thèmes médicaux des events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmtStart = '
            SELECT
                medic_event_themes_relation.*,
                medic_theme_list.name
            FROM
                medic_event_list
            INNER JOIN medic_event_themes_relation ON medic_event_list.medicEventID = medic_event_themes_relation.medicEventID
            INNER JOIN medic_theme_list ON medic_event_themes_relation.medicThemeID = medic_theme_list.medicThemeID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        $stmtEnd = 'ORDER BY medic_theme_list.name;';

        return $stmtStart . $whereString . $stmtEnd;
    }
}
