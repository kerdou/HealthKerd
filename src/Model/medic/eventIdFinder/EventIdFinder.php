<?php

namespace HealthKerd\Model\medic\eventIdFinder;

/** Classe dédiée à la recherche d'ID d'events
 */
class EventIdFinder extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récupére les ID des events à venir d'un user
     * @return array    Liste des ID des events à venir
     */
    public function comingEventsIds()
    {
        $stmt =
            "SELECT
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

    /** Récupére les ID tous les events d'un user
     * @return array    Liste des ID des events du user
     */
    public function eventsIdsByUserId()
    {
        $stmt =
            "SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                userID = " . $_SESSION['userID'] . "
            ;";

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }

    /** Récupére les ID des events d'un user par rapport à une catégorie
     * -----
     * * Requête préparée
     * @param string $medicEventCatID   ID de la catégorie d'events
     * @return array                    Liste des ID des events concernés
     */
    public function eventsIdsbyCatId(string $medicEventCatID)
    {
        $stmt =
            "SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                medicEventCatID = :medicEventCatID
            AND
                userID = :userID;";
        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':medicEventCatID', $medicEventCatID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }

    /** Récupére les ID des events d'un user par rapport à un cabinet médical
     * -----
     * * Requête préparée
     * @param string $docOfficeID       ID du cabinet médical
     * @return array                    Liste des ID d'events concernés
     */
    public function eventsIdsByDocOfficeId(string $docOfficeID)
    {
        $stmt =
            "SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                docOfficeID = :docOfficeID
            AND
                userID = :userID;";
        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':docOfficeID', $docOfficeID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }

    /** Récupére les ID des events d'un user par rapport à un docteur
     * -----
     * * Requête préparée
     * @param string $medicEventCatID   ID de la catégorie d'events
     * @return array                    Liste des ID d'events concernés
     */
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

    /** Méthode de test pour le développement, récupére un event en particulier
     * @return array         Liste des ID d'events concernés
     */
    public function onlyOneEvent()
    {
        $stmt =
            "SELECT
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
