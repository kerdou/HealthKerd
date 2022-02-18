<?php

namespace HealthKerd\Model\modelInit\medic\doc;

/** Modéle POST de modification de la base vis à vis des docteurs
 */
class DocInsertModel extends \HealthKerd\Model\common\ModelInChief
{
    public object $mapper;

    public function __construct()
    {
        parent::__construct();
        $this->mapper = new \HealthKerd\Model\tableMappers\medic\doc\DocInsertMapper();
    }

    public function __destruct()
    {
    }

    /** Ajout d'un docteur
     * ----
     * * Requête préparée
     * @param array $cleanedUpPost      Données nettoyées et vérifiée du docteur à créer
     * @return string                   Message d'erreur s'il y en a eu
     */
    public function addDocModel(array $cleanedUpPost)
    {
        $docStmt = "";
        $this->mapper->addDocMapper();
        $docStmt = $this->mapper->maps['InsertDocList']->addDocStmt();
        $this->query = $this->pdo->prepare($docStmt);
        $this->query->bindParam(':userID', $_SESSION['userID']);
        $this->query->bindParam(':title', $cleanedUpPost['title']);
        $this->query->bindParam(':lastName', $cleanedUpPost['lastname']);
        $this->query->bindParam(':firstName', $cleanedUpPost['firstname']);
        $this->query->bindParam(':tel', $cleanedUpPost['tel']);
        $this->query->bindParam(':mail', $cleanedUpPost['mail']);
        $this->query->bindParam(':webPage', $cleanedUpPost['webpage']);
        $this->query->bindParam(':doctolibPage', $cleanedUpPost['doctolibpage']);
        $this->query->bindParam(':comment', $cleanedUpPost['comment']);

        $pdoErrorMessage = '';
        $pdoErrorMessage = $this->pdoPreparedInsertUpdateDeleteExecute();
        return $pdoErrorMessage;
    }

    /** Ajout automatique d'un cabinet médical et de spécialités médicales suite à la création d'un docteur
     * TODO: Modifier pour ne pas assigner une spé médicale et un cabinet par défaut
     * ----
     * * Requête préparée
     * @param string $newDocID      ID du docteur nouvellement créé
     */
    public function addOfficeAndSpeMedicModel(string $newDocID)
    {
        $this->pdo->beginTransaction(); // permet de faire plusieurs requetes préparées en une passe
        $this->mapper->addOfficeAndSpeMedicMapper();

        // gestion de la requete de docOffice
        $docOfficeStmt = '';
        $docOfficeStmt = $this->mapper->maps['InsertDocDocofficeRelation']->addDocDocofficeRelationStmt();
        $docOfficeQuery = $this->pdo->prepare($docOfficeStmt);
        $docOfficeQuery->bindParam(':docID', $newDocID);
        $docOfficeQuery->execute();

        // gestion de la requete de speMedic
        $speMedicStmt = '';
        $speMedicStmt = $this->mapper->maps['InsertDocSpemedicRelation']->addDocSpemedicRelationStmt();
        $speMedicQuery = $this->pdo->prepare($speMedicStmt);
        $speMedicQuery->bindParam(':docID', $newDocID);
        $speMedicQuery->execute();

        $this->pdo->commit(); // execution de la requete
    }
}
