<?php

namespace HealthKerd\Model\modelInit\medic\doc;

/** Modéle POST de modification de la base vis à vis des docteurs
 */
class DocUpdateModel extends \HealthKerd\Model\common\ModelInChief
{
    private object $mapper;

    public function __construct()
    {
        parent::__construct();
        $this->mapper = new \HealthKerd\Model\tableMappers\medic\doc\DocUpdateMapper();
    }

    public function __destruct()
    {
    }

    /** Modification d'un docteur
     * ----
     * * Requête préparée
     * @param array $cleanedUpPost      Données nettoyées et vérifiée du docteur à modifier
     * @param string $docID             ID du docteur
     * @return string                   Message d'erreur s'il y en a eu
     */
    public function editDocModel(array $cleanedUpPost, string $docID): string
    {
        $docStmt = '';
        $this->mapper->displayAllDocsListMapper();
        $docStmt = $this->mapper->maps['UpdateDocList']->docEditStmt();

        $this->query = $this->pdo->prepare($docStmt);
        $this->query->bindParam(':title', $cleanedUpPost['title']);
        $this->query->bindParam(':lastName', $cleanedUpPost['lastname']);
        $this->query->bindParam(':firstName', $cleanedUpPost['firstname']);
        $this->query->bindParam(':tel', $cleanedUpPost['tel']);
        $this->query->bindParam(':mail', $cleanedUpPost['mail']);
        $this->query->bindParam(':webPage', $cleanedUpPost['webpage']);
        $this->query->bindParam(':doctolibPage', $cleanedUpPost['doctolibpage']);
        $this->query->bindParam(':comment', $cleanedUpPost['comment']);
        $this->query->bindParam(':docID', $docID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $pdoErrorMessage = '';
        $pdoErrorMessage = $this->pdoPreparedInsertUpdateDeleteExecute();

        return $pdoErrorMessage;
    }

    /** Suppression puis mise à jour des spé médicales du doc
     * ----
     * Requetes préparées
     */
    public function editSpeMedForDocModel(array $speMedicIDArray)
    {
        $this->pdo->beginTransaction();

        // doc_spemedic_relation: supprimer toutes les lignes ayant le docID
        $docSpemedicRelationDeleteStmt = 'DELETE FROM doc_spemedic_relation WHERE docID = :docID;';
        $this->query = $this->pdo->prepare($docSpemedicRelationDeleteStmt);
        $this->query->bindParam('docID', $_SESSION['checkedDocID']);
        $this->pdoPreparedInsertUpdateDeleteExecute();

        // doc_spemedic_relation: ajouter toutes les nouvelles speMedicID rattachées au docID
        if (sizeof($speMedicIDArray) > 0) {
            $docSpemedicRelationInsertStmt = 'INSERT INTO doc_spemedic_relation VALUES (:docID, :speMedicID)';
            $this->query = $this->pdo->prepare($docSpemedicRelationInsertStmt);
            foreach ($speMedicIDArray as $key => $speMedicID) {
                $this->query->bindParam('docID', $_SESSION['checkedDocID']);
                $this->query->bindParam('speMedicID', $speMedicID);
                $this->pdoPreparedInsertUpdateDeleteExecute();
            }
        }

        $this->pdo->commit(); // execution de la requete
    }

    /** Suppression puis mise à jour des doc offices du doc
     * ----
     * Requetes préparées
     */
    public function editDocOfficeForDocModel(array $docOfficeIDArray)
    {
        $this->pdo->beginTransaction();

        // doc_docoffice_relation: supprimer toutes les lignes ayant le docID
        $docDocofficeRelationDeleteStmt = 'DELETE FROM doc_docoffice_relation WHERE docID = :docID;';
        $this->query = $this->pdo->prepare($docDocofficeRelationDeleteStmt);
        $this->query->bindParam('docID', $_SESSION['checkedDocID']);
        $this->pdoPreparedInsertUpdateDeleteExecute();

        // doc_docoffice_relation: ajouter tous les nouveaux docOfficeID
        if (sizeof($docOfficeIDArray) > 0) {
            $docDocofficeRelationDeleteStmt = 'INSERT INTO doc_docoffice_relation VALUES (:docID, :docOfficeID);';
            $this->query = $this->pdo->prepare($docDocofficeRelationDeleteStmt);
            $this->query->bindParam('docID', $_SESSION['checkedDocID']);
            foreach ($docOfficeIDArray as $key => $docOfficeID) {
                $this->query->bindParam('docID', $_SESSION['checkedDocID']);
                $this->query->bindParam('docOfficeID', $docOfficeID);
                $this->pdoPreparedInsertUpdateDeleteExecute();
            }
        }

        $this->pdo->commit(); // execution de la requete
    }
}
