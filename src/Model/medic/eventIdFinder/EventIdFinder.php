<?php

namespace HealthKerd\Model\medic\eventIdFinder;

/** Model de la section home */
class EventIdFinder extends \HealthKerd\Model\common\ModelInChief
{
    /** */
    public function __construct()
    {
        parent::__construct();
    }


    /** */
    public function comingEventsIds()
    {
        $stmt = "SELECT
                    medicEventID
                FROM
                    medic_event_list
                WHERE
                    userID = " . $_SESSION['userID'] . "
                    AND
                    dateTime >= CURRENT_DATE
                ORDER BY
                    dateTime;";

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }


    /** */
    public function eventsIdsByUserId()
    {
        $stmt = "SELECT
                    medicEventID
                FROM
                    medic_event_list
                WHERE
                    userID = " . $_SESSION['userID'] . "
                ;";

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }


    /** */
    public function eventsIdsFromOneDocId(string $docID)
    {
        $stmt =
            "SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                docID = :docID
            AND
                userID = :userID;";
        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':docID', $docID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }

    /** */
    public function onlyOneEvent()
    {
        $stmt = "SELECT
                    medicEventID
                FROM
                    medic_event_list
                WHERE
                    medicEventID = 54
                ;";

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }
}
