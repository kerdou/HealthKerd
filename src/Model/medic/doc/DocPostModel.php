<?php

namespace HealthKerd\Model\medic\doc;

/** Model de la section 'client' */
class DocPostModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** */
    public function addDoc(array $cleanedUpPost)
    {
        $docStmt =
        "INSERT INTO doc_list
        VALUES (
            NULL,
            :userID,
            0,
            0,
            0,
            0,
            0,
            0,
            :title,
            :lastName,
            :firstName,
            :tel,
            :mail,
            :webPage,
            :doctolibPage,
            :comment,
            CURRENT_DATE()
        );";

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


    public function addOfficeAndSpeMedic(string $newDocID)
    {
        $docOfficeStmt =
            "INSERT INTO
                doc_docoffice_relation
            VALUES (:docID, 1);";
        $docOfficeQuery = $this->pdo->prepare($docOfficeStmt);

        $speMedicStmt =
            "INSERT INTO
                doc_spemedic_relation
            VALUES (:docID, 48);";
        $speMedicQuery = $this->pdo->prepare($speMedicStmt);

        $this->pdo->beginTransaction(); // permet de faire plusieurs requetes préparées en une passe

        // gestion de la requete de docOffice
        $docOfficeQuery->bindParam(':docID', $newDocID);
        $docOfficeQuery->execute();

        // gestion de la requete de speMedic
        $speMedicQuery->bindParam(':docID', $newDocID);
        $speMedicQuery->execute();

        $this->pdo->commit(); // execution de la requete
    }



    /** */
    public function editDoc(array $cleanedUpPost, string $docID)
    {
        $docStmt =
            "UPDATE
                doc_list
            SET
                title = :title,
                lastName = :lastName,
                firstName = :firstName,
                tel = :tel,
                mail = :mail,
                webPage = :webPage,
                doctolibPage = :doctolibPage,
                comment = :comment
            WHERE
                docID = :docID
            AND
                userID = :userID
            ;";

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


    public function deleteDoc(string $docID)
    {
        $stmt =
            "DELETE FROM
                doc_list
            WHERE
                docID = :docID
            AND
                userID = :userID
            ";
        $this->query = $this->pdo->prepare($stmt);


        $this->query->bindParam(':docID', $docID);
        $this->query->bindParam(':userID', $_SESSION['userID']);


        $pdoErrorMessage = '';
        $pdoErrorMessage = $this->pdoPreparedInsertUpdateDeleteExecute();

        return $pdoErrorMessage;
    }


    public function deleteOfficeAndSpeMedic(string $newDocID)
    {
        $docOfficeStmt =
            "DELETE FROM
                doc_docoffice_relation
            WHERE
                docID = :docID
            AND
                docOfficeID = 1;";
        $docOfficeQuery = $this->pdo->prepare($docOfficeStmt);

        $speMedicStmt =
            "DELETE FROM
                doc_spemedic_relation
            WHERE
                docID = :docID
            AND
                speMedicID = 48;";
        $speMedicQuery = $this->pdo->prepare($speMedicStmt);

        $this->pdo->beginTransaction(); // permet de faire plusieurs requetes préparées en une passe

        // gestion de la requete de docOffice
        $docOfficeQuery->bindParam(':docID', $newDocID);
        $docOfficeQuery->execute();

        // gestion de la requete de speMedic
        $speMedicQuery->bindParam(':docID', $newDocID);
        $speMedicQuery->execute();

        $this->pdo->commit(); // execution de la requete
    }
}
