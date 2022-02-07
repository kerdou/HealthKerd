<?php

namespace HealthKerd\Model\medic\eventCat;

/** Model GET des catégories d'events
 */
class EventCatModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récuperer les données de toutes les catégories d'events utilisées par un user
     * -----
     * * Requête préparée
     * @return array        Liste des infos de toutes les spécialités médicales concernées
     */
    public function gatherAllEventsCats()
    {
        $stmt =
            "SELECT
                *
            FROM
                medic_event_category
            WHERE
                userID = :userID
            ORDER BY
                'name';";

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }
}
