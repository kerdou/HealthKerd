<?php

namespace HealthKerd\Model\medic\speMedic;

/** Model de la section 'client' */
class SpeMedicModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }



    public function speMedicByEventsIds(array $medicEventsIdList)
    {
        $whereString = $this->stmtWhereBuilder($medicEventsIdList, 'medicEventID');

        $stmt =
            "SELECT
                *
            FROM
                medic_event_spemedic_relation
            WHERE
                " . $whereString . ";";

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }


    /** */
    public function gatherMedicEventSpeMedicRelation(array $eventIDList)
    {
        $result = '';

        if (sizeof($eventIDList)) {
            $whereString = $this->stmtWhereBuilder($eventIDList, 'medicEventID');

            $stmt =
                "SELECT
                    medic_event_spemedic_relation.*,
                    spe_medic_full_list.name
                FROM
                    medic_event_spemedic_relation
                INNER JOIN spe_medic_full_list ON medic_event_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
                WHERE " . $whereString . ";";

            $result = $this->pdoRawSelectExecute($stmt, 'multi');
        }

        return $result;
    }
}
