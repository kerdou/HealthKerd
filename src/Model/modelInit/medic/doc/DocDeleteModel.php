<?php

namespace HealthKerd\Model\modelInit\medic\doc;

/** Modéle POST de modification de la base vis à vis des docteurs
 */
class DocDeleteModel extends \HealthKerd\Model\common\ModelInChief
{
    private object $mapper;

    public function __construct()
    {
        parent::__construct();
        $this->mapper = new \HealthKerd\Model\tableMappers\medic\doc\DocDeleteMapper();
    }

    public function __destruct()
    {
    }

    /** Suppression d'un docteur
     * ----
     * * Requête préparée
     * @param string $docID             ID du docteur
     * @return string                   Message d'erreur s'il y en a eu
     */
    public function deleteDoc(string $docID): string
    {
        $stmt = "";
        $this->mapper->deleteDocMapper();
        $stmt = $this->mapper->maps['DeleteDocList']->deleteDocStmt();
        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':docID', $docID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $pdoErrorMessage = '';
        $pdoErrorMessage = $this->pdoPreparedInsertUpdateDeleteExecute();

        return $pdoErrorMessage;
    }
}
