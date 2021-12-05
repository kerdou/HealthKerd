<?php

namespace HealthKerd\Model\medic\docOffice;

/** Model de la section 'client' */
class DocOfficeModel extends \HealthKerd\Model\common\ModelInChief
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
    public function gatherAllDocOffices()
    {
        $stmt =
            "SELECT
                docOfficeID, name, cityName
            FROM
                doc_office_list
            WHERE
                userID = :userID
            ORDER BY
                name;";

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }
}
