<?php

namespace HealthKerd\Model\sqlStmtStore\medicThemeList;

/** Classe dédiée à la table medic_theme_list
 * * Sujet: Liste les thèmes médicaux utilisé par les users
 */
class SelectMedicThemesList
{
    public function __destruct()
    {
    }

    /** Génére la déclaration SQL pour les thèmes médicaux créés par un user
     * @return string               Déclaration SQL complète
     */
    public function selectMedicThemeCreatedByUserIdStmt(): string
    {
        $stmt =
            'SELECT
                medic_theme_list.medicThemeID,
                medic_theme_list.name
            FROM
                medic_theme_list
            WHERE
                medic_theme_list.userID = ' . $_SESSION['userID'] .
            ' ORDER BY medic_theme_list.name;';

        /*
            SELECT
                medic_theme_list.medicThemeID,
                medic_theme_list.name
            FROM
                medic_theme_list
            WHERE
                medic_theme_list.userID = 3
            ORDER BY medic_theme_list.name;
        */



        return $stmt;
    }

    /** Génére la déclaration SQL pour les thèmes médicaux utilisés par un user
     * @return string               Déclaration SQL complète
     */
    public function selectMedicThemeUsedByUserIdStmt(): string
    {

        $stmt =
            'SELECT DISTINCT
                medic_theme_list.medicThemeID,
                medic_theme_list.name
            FROM
                medic_event_list
            INNER JOIN medic_event_themes_relation ON medic_event_list.medicEventID = medic_event_themes_relation.medicEventID
            INNER JOIN medic_theme_list ON medic_event_themes_relation.medicThemeID = medic_theme_list.medicThemeID
            WHERE
                medic_event_list.userID = ' . $_SESSION['userID'] .
            ' ORDER BY
                medic_theme_list.name;';

        /*
            SELECT DISTINCT
                medic_theme_list.medicThemeID,
                medic_theme_list.name
            FROM
                medic_event_list
            INNER JOIN medic_event_themes_relation ON medic_event_list.medicEventID = medic_event_themes_relation.medicEventID
            INNER JOIN medic_theme_list ON medic_event_themes_relation.medicThemeID = medic_theme_list.medicThemeID
            WHERE
                medic_event_list.userID = 1
            ORDER BY medic_theme_list.name;
        */

        return $stmt;
    }
}
