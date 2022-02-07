<?php

namespace HealthKerd\Model\medic\doc;

/** Modéle GET de récupération des données des docteurs et des autre données gravitant autour
 */
class DocModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récupération des données basiques de tous les docteurs d'un user
     * * docID
     * * Titre du doc
     * * Prénom du doc
     * * Nom de famille du doc
     * ----
     * * Requête préparée
     * @return array    Renvoie de toutes les données
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

    /** Récupération des données de la page d'informations concernant un docteur
     * * Données du docteur
     * * Ses spécialités médicales
     * * Son/ses cabinets médicaux
     * ------
     * * Requête préparée
     * @param string $docID     ID du docteur concerné
     * @return array            Toutes les informations renvoyées par la base
     */
    public function getOneDocForPageDisplay(string $docID)
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

        $speMedicStmt =
            "SELECT
                doc_spemedic_relation.*,
                spe_medic_full_list.name
            FROM
                doc_spemedic_relation
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE docID = :docID;";
        $speMedicQuery = $this->pdo->prepare($speMedicStmt);

        $docOfficestmt =
            "SELECT
                doc_docoffice_relation.*,
                doc_office_list.name,
                doc_office_list.cityName
            FROM
                doc_docoffice_relation
            INNER JOIN doc_office_list ON doc_docoffice_relation.docOfficeID = doc_office_list.docOfficeID
            WHERE docID = :docID;";
        $docOfficeQuery = $this->pdo->prepare($docOfficestmt);

        $medicEventStmt =
            "SELECT
                *
            FROM
                medic_event_list
            WHERE
                docID = :docID
            AND
                userID = :userID;";
        $medicEventQuery = $this->pdo->prepare($medicEventStmt);

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

        // gestion de la requete pour le docOffice
        $docOfficeQuery->bindParam(':docID', $docID);
        $docOfficeQuery->execute();
        $docOfficeResult = $docOfficeQuery->fetchAll(\PDO::FETCH_ASSOC);

        // gestion de la requete pour les events
        $medicEventQuery->bindParam(':docID', $docID);
        $medicEventQuery->bindParam(':userID', $_SESSION['userID']);
        $medicEventQuery->execute();
        $medicEventResult = $medicEventQuery->fetchAll(\PDO::FETCH_ASSOC);

        $this->pdo->commit(); // execution des requetes

        // fermeture des connexions à la DB
        $docQuery->closeCursor();
        $speMedicQuery->closeCursor();

        return $result = [
            'doc' => $docResult,
            'speMedic' => $speResult,
            'docOffice' => $docOfficeResult,
            'medicEvent' => $medicEventResult
        ];
    }

    /** Récupération de toutes les données d'un docteur pour l'affichage dans un formulaire
     * * Requête préparée
     * @param string $docID     ID du docteur
     * @return array            Toutes les données du docteur depuis doc_list
     */
    public function getOneDocForFormDisplay(string $docID)
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

        // gestion de la requete pour le doc
        $docQuery->bindParam(':docID', $docID);
        $docQuery->bindParam(':userID', $_SESSION['userID']);
        $docQuery->execute();
        $docResult = $docQuery->fetch(\PDO::FETCH_ASSOC);

        return $docResult;
    }


    /** Récupération des spécialités médicales d'un docteur
     * @param array $docIDList
     * @return array
    */
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

    /** Récupération de l'ID du dernier docteur créé par le user
     * @return string   Le docID
    */
    public function getNewDocID()
    {
        $stmt =
            "SELECT
                docID
            FROM
                doc_list
            WHERE " . $_SESSION['userID'] . "
            ORDER BY docID
            DESC LIMIT 1
            ;";

        $result = $this->pdoRawSelectExecute($stmt, 'single');

        return $result;
    }
}
