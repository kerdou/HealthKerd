<?php

namespace HealthKerd\Model\modelInit\medic\docOffice;

/** Model SELECT des cabinets médicaux
 */
class DocOfficeSelectModel extends \HealthKerd\Model\common\ModelInChief
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
    public function gatherAllDocOfficesModel()
    {
        $mapper = new \HealthKerd\Model\tableMappers\medic\docOffice\DocOfficeSelectMapper();
        $mapper->gatherAllDocOfficesMapper();

        $stmt = "";
        $stmt = $mapper->maps['SelectDocOffice']->gatherAllDocOffices();

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }
}
