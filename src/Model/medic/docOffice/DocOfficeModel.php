<?php

namespace HealthKerd\Model\medic\docOffice;

/** Model GET des cabinets médicaux
 */
class DocOfficeModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récuperer les données basiques de tous les cabinets médicaux consultés par un user
     * -----
     * * Requête préparée
     * @return array        Liste des infos de tous les cabinets médicaux concernés
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
