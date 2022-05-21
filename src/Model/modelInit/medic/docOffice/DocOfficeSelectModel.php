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

        $this->pdo->beginTransaction(); // permet de faire plusieurs requetes préparées en une passe

        // récupération des données des cabinets médicaux
        $docOfficeStmt = $mapper->maps['SelectDocOffice']->gatherAllDocOffices();
        $docOfficeQuery = $this->pdo->prepare($docOfficeStmt);
        $docOfficeQuery->bindParam(':userID', $_SESSION['userID']);
        $docOfficeQuery->execute();
        $docOfficeResult = $docOfficeQuery->fetchAll(\PDO::FETCH_ASSOC);

        // récupération des données des spécialités médicales des cabinets médicaux
        $docOfficeSpeMedicStmt = $mapper->maps['SelectDocofficeSpemedicRelation']->preparedSelectEverySpeMedicOfAllDocOfficesOfUser();
        $docOfficeSpeMedicQuery = $this->pdo->prepare($docOfficeSpeMedicStmt);
        $docOfficeSpeMedicQuery->bindParam(':userID', $_SESSION['userID']);
        $docOfficeSpeMedicQuery->execute();
        $docOfficeSpeMedicResult = $docOfficeSpeMedicQuery->fetchAll(\PDO::FETCH_ASSOC);

        $this->pdo->commit(); // execution des requetes

        // fermeture des connexions à la DB
        $docOfficeQuery->closeCursor();
        $docOfficeSpeMedicQuery->closeCursor();

        return $result = [
            'docOffice' => $docOfficeResult,
            'docOfficeSpeMedic' => $docOfficeSpeMedicResult
        ];
    }
}
