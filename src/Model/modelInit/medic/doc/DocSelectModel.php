<?php

namespace HealthKerd\Model\modelInit\medic\doc;

/** Modéle GET de récupération des données des docteurs et des autre données gravitant autour
 */
class DocSelectModel extends \HealthKerd\Model\common\PdoBufferManager
{
    private object $mapper;

    public function __construct()
    {
        parent::__construct();
        $this->mapper = new \HealthKerd\Model\tableMappers\medic\doc\DocSelectMapper();
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
    public function getDataForOneDocPageModel(string $docID): array
    {
        $this->mapper->getDataForOneDocPageMapper();

        $this->pdo->beginTransaction(); // permet de faire plusieurs requetes préparées en une passe

        // gestion de la requete pour le doc
        $docStmt = $this->mapper->maps['SelectDocList']->selectPrepAllDataFromOneDocStmt();
        $docQuery = $this->pdo->prepare($docStmt);
        $docQuery->bindParam(':docID', $docID);
        $docQuery->bindParam(':userID', $_SESSION['userID']);
        $docQuery->execute();
        $docResult = $docQuery->fetch(\PDO::FETCH_ASSOC);

        // gestion de la requete pour les speMedics
        $speMedicStmt = $this->mapper->maps['SelectDocSpemedicRelation']->selectPreparedDocSpemedicRelationStmt();
        $speMedicQuery = $this->pdo->prepare($speMedicStmt);
        $speMedicQuery->bindParam(':docID', $docID);
        $speMedicQuery->execute();
        $speResult = $speMedicQuery->fetchAll(\PDO::FETCH_ASSOC);

        // gestion de la requete pour le docOffice
        $docOfficestmt = $this->mapper->maps['SelectDocDocofficeRelation']->gatherAllDocOfficesBasicsStmt();
        $docOfficeQuery = $this->pdo->prepare($docOfficestmt);
        $docOfficeQuery->bindParam(':docID', $docID);
        $docOfficeQuery->execute();
        $docOfficeResult = $docOfficeQuery->fetchAll(\PDO::FETCH_ASSOC);

        // gestion de la requete pour les events
        $medicEventStmt = $this->mapper->maps['SelectMedicEventList']->eventsFromOneDocIdStmt();
        $medicEventQuery = $this->pdo->prepare($medicEventStmt);
        $medicEventQuery->bindParam(':docID', $docID);
        $medicEventQuery->bindParam(':userID', $_SESSION['userID']);
        $medicEventQuery->execute();
        $medicEventResult = $medicEventQuery->fetchAll(\PDO::FETCH_ASSOC);

        $this->pdo->commit(); // execution des requetes

        // fermeture des connexions à la DB
        $docQuery->closeCursor();
        $speMedicQuery->closeCursor();
        $docOfficeQuery->closeCursor();
        $medicEventQuery->closeCursor();

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
    public function getAllDataForOneDocFromDocListModel(string $docID): array
    {
        $this->mapper->getAllDataForOneDocFromDocListMapper();

        $docStmt = $this->mapper->maps['SelectDocList']->selectPrepAllDataFromOneDocStmt();
        $docQuery = $this->pdo->prepare($docStmt);
        $docQuery->bindParam(':docID', $docID);
        $docQuery->bindParam(':userID', $_SESSION['userID']);
        $docQuery->execute();
        $docResult = $docQuery->fetch(\PDO::FETCH_ASSOC);

        return $docResult;
    }

    /** Récupére les données basiques des docteurs d'un user ainsi que leurs spé médicales
     * @return array        Donnée des docteurs et leurs spé médicales
     */
    public function displayAllDocsListModel(): array
    {
        $docsUsedByUserWhereString = $this->stmtWhereBuilder([$_SESSION['userID']], 'doc_list.userID');

        $this->mapper->displayAllDocsListMapper();

        /** Récupération des déclarations pour chaque demande:
         * * Liste des docteurs consultés par le users
         * * Liste des spé médicales de chaque docteurs
         * * Liste de toutes les spé médicales consultées par le user
         */
        $dataStore['doc_list']['pdoStmt'] = $this->mapper->maps['SelectDocList']->stmtAllDocsBasicsInfos($docsUsedByUserWhereString);
        $dataStore['doc_spemedic_relation']['pdoStmt'] = $this->mapper->maps['SelectDocSpemedicRelation']->stmtDocSpeMedicRelation($docsUsedByUserWhereString);
        $dataStore['used_spemedics']['pdoStmt'] = $this->mapper->maps['SelectDocSpemedicRelation']->stmtSpeMedicUsedByUser($docsUsedByUserWhereString);

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

    /** Récupération de l'ID du dernier docteur créé par le user
     * @return string   Le docID
    */
    public function getNewDocIDModel(): string
    {
        $stmt = '';
        $this->mapper->getNewDocIDMapper();
        $stmt = $this->mapper->maps['SelectDocList']->getNewDocIDStmt();
        $result = $this->pdoRawSelectExecute($stmt, 'single');

        return $result = $result['docID'];
    }
}
