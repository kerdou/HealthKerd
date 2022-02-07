<?php

namespace HealthKerd\Model\medic\medicTheme;

/** Classe dédiée à la recherche de thèmes médicaux
 */
class MedicThemeModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récupére les thèmes médicaux ainsi que leur nom liés à une liste d'events
     * @param array $medicEventsIdList      Liste des events concernés
     * @return array                        Liste des thèmes médicaux demandés
     */
    public function medicThemeByEventsIds(array $medicEventsIdList)
    {
        $whereString = $this->stmtWhereBuilder($medicEventsIdList, 'medicEventID');

        $stmt =
            "SELECT
                medic_event_themes_relation.*,
                medic_theme_list.name
            FROM
                medic_event_themes_relation
            INNER JOIN medic_theme_list ON medic_event_themes_relation.medicThemeID = medic_theme_list.medicThemeID
            WHERE
                " . $whereString . ";";

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }

    /** Récupération de tous les thèmes médicaux trouvés dans les events d'un user
     * @param array $medicEventsIdList      Liste des events d'un user
     * @return array                        Liste des thèmes médicaux demandés
     */
    public function gatherMedicEventMedicThemeRelation(array $medicEventsIdList)
    {
        $result = array();

        if (sizeof($medicEventsIdList)) {
            $whereString = $this->stmtWhereBuilder($medicEventsIdList, 'medicEventID');

            $stmt =
                "SELECT
                    *
                FROM
                    medic_event_themes_relation
                WHERE " . $whereString . ";";

            $result = $this->pdoRawSelectExecute($stmt, 'multi');
        }

        return $result;
    }
}
