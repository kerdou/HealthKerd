<?php

namespace HealthKerd\Model\medic\eventCat;

/** Model de la section 'client' */
class EventCatModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récupération des identifiants dans la base selon le userLogin envoyé par le user
     * @param array $postArray Contient les paramètres du $_POST
     * @return array Renvoie les infos du user
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
