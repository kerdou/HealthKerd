<?php

namespace HealthKerd\Model\medic\doc;

/** Modéle GET de récupération des données des docteurs et des autre données gravitant autour
 */
class DocModel extends \HealthKerd\Model\common\PdoBufferManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
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
    public function getDataForOneDocPage(string $docID): array
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
    public function getAllDataForOneDocFromDocList(string $docID): array
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

    /** Récupére les données basiques des docteurs d'un user ainsi que leurs spé médicales
     * @return array        Donnée des docteurs et leurs spé médicales
     */
    public function displayAllDocList(): array
    {
        $medicSqlStmtStore = new  \HealthKerd\Model\medicSqlStmtStore\MedicSqlStmtStore(); // accés au store de templates de requêtes SQL
        $docsUsedByUserWhereString = $this->stmtWhereBuilder([$_SESSION['userID']], 'doc_list.userID');

        $dataStore = array();
        $dataStore['doc_list']['pdoStmt'] = $medicSqlStmtStore->DocList->stmtAllDocsBasicsInfos($docsUsedByUserWhereString);
        $dataStore['doc_spemedic_relation']['pdoStmt'] = $medicSqlStmtStore->DocSpemedicRelation->stmtDocSpeMedicRelation($docsUsedByUserWhereString);
        $dataStore['used_spemedics']['pdoStmt'] = $medicSqlStmtStore->DocSpemedicRelation->stmtSpeMedicUsedByUser($docsUsedByUserWhereString);

        // Lancement de l'injection des données dans le $pdoBufferArray
        foreach ($dataStore as $key => $value) {
            $this->pdoStmtAndDestInsertionInCue($value['pdoStmt'], $key);
        }

        $dataToWrite = array();
        $dataToWrite = $this->pdoQueryExec(); // traitement des requêtes en attente dans le $pdoBuffer

        // extraction des destinations des données puis remplacement des clés dans $dataToWrite['pdoMixedResult']
        $dataToWrite['finalDestKeys'] = array();
        foreach ($dataToWrite['dest'] as $key => $value) {
            array_push($dataToWrite['finalDestKeys'], $value[0]);
        }
        $dataToWrite['pdoMixedResult'] = array_combine($dataToWrite['finalDestKeys'], $dataToWrite['pdoMixedResult']);

        return $dataToWrite['pdoMixedResult'];
    }
}
