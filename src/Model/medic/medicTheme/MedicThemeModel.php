<?php

namespace HealthKerd\Model\medic\medicTheme;

/** Model de la section 'client' */
class MedicThemeModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }


    /** */
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


    /** */
    public function gatherMedicEventMedicThemeRelation(array $eventIDList)
    {
        $result = '';

        if (sizeof($eventIDList)) {
            $whereString = $this->stmtWhereBuilder($eventIDList, 'medicEventID');

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
