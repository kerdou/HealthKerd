<?php

namespace HealthKerd\Model\medic\doc;

/** Model de la section 'client' */
class DocModel extends \HealthKerd\Model\common\ModelInChief
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
    public function gatherAllDocs()
    {
        $stmt =
            "SELECT
                docID, title, firstName, lastName
            FROM
                doc_list
            WHERE
                userID = :userID
            ORDER BY
                lastName;";

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }


    /** Récupération des identifiants dans la base selon le userLogin envoyé par le user
     * @param array $postArray Contient les paramètres du $_POST
     * @return array Renvoie les infos du user
     */
    public function getOneDoc($docID)
    {
        $docStmt =
            "SELECT
                *
            FROM
                doc_list
            WHERE
                docID = :docID
            HAVING
                userID = :userID;";
        $docQuery = $this->pdo->prepare($docStmt);

        $speMedicstmt =
            "SELECT
                doc_spemedic_relation.*,
                spe_medic_full_list.name
            FROM
                doc_spemedic_relation
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE docID = :docID;";
        $speMedicQuery = $this->pdo->prepare($speMedicstmt);


        $this->pdo->beginTransaction(); // permet de faire plusieurs requetes préparées en une passe


        // gestion de la requete pour le doc
        $docQuery->bindParam(':docID', $docID);
        $docQuery->bindParam(':userID', $_SESSION['userID']);
        $docQuery->execute();
        $docResult = $docQuery->fetch(\PDO::FETCH_ASSOC);


        // gestion de la requete pour les speMedics
        $speMedicQuery->bindParam(':docID', $docID);
        $speMedicQuery->execute();
        $speResult = $speMedicQuery->fetchAll(\PDO::FETCH_ASSOC);

        $this->pdo->commit(); // execution des requetes

        // fermeture des connexions à la DB
        $docQuery->closeCursor();
        $speMedicQuery->closeCursor();

        return $result = ['doc' => $docResult, 'speMedic' => $speResult];
    }




    /** */
    public function gatherDocSpeMedicRelation(array $docIDList)
    {
        $whereString = $this->stmtWhereBuilder($docIDList, 'docID');

        $stmt =
            "SELECT
                doc_spemedic_relation.*,
                spe_medic_full_list.name
            FROM
                doc_spemedic_relation
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE " . $whereString . ";";

        $result = $this->pdoRawSelectExecute($stmt, 'multi');

        return $result;
    }
}
