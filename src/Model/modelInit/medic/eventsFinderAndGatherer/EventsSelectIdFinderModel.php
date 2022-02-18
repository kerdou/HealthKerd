<?php

namespace HealthKerd\Model\modelInit\medic\eventsFinderAndGatherer;

/** Classe de récupération du contenu des events suivant les eventID demandés */
class EventsSelectIdFinderModel extends \HealthKerd\Model\common\PdoBufferManager
{
    private object $idFinderMapper;

    public function __construct()
    {
        parent::__construct();
        $this->idFinderMapper = new \HealthKerd\Model\tableMappers\medic\eventsFinderAndGatherer\EventIdFinderSelectMapper();
        $this->idFinderMapper->medicEventListMapping();
    }

    public function __destruct()
    {
    }

    /** Récupére les ID des events à venir d'un user
     * @return array    Liste des ID des events à venir
     */
    public function comingEventsIds(): array
    {
        $stmt = '';
        $stmt = $this->idFinderMapper->maps['MedicEventList']->comingEventsIdsStmt();

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }

    /** Récupére les ID tous les events d'un user
     * @return array    Liste des ID des events du user
     */
    public function eventsIdsByUserId(): array
    {
        $stmt = '';
        $stmt = $this->idFinderMapper->maps['MedicEventList']->eventsIdsByUserIdStmt();

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }

    /** Récupére les ID des events d'un user par rapport à une catégorie
     * -----
     * * Requête préparée
     * @param string $medicEventCatID   ID de la catégorie d'events
     * @return array                    Liste des ID des events concernés
     */
    public function eventsIdsbyCatId(string $medicEventCatID): array
    {
        $stmt = '';
        $stmt = $this->idFinderMapper->maps['MedicEventList']->eventsIdsbyCatIdStmt();

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':medicEventCatID', $medicEventCatID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }

    /** Récupére les ID des events d'un user par rapport à un thème médical
     * -----
     * * Requête préparée
     * @param string $medicEventCatID   ID du thème médical
     * @return array                    Liste des ID des events concernés
     */
    public function eventsIdsFromMedicThemeId(string $medicThemeID): array
    {
        $stmt = '';
        $stmt = $this->idFinderMapper->maps['MedicEventList']->eventsIdsFromMedicThemeIdStmt();

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':medicThemeID', $medicThemeID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }

    /** Récupére les ID des events d'un user par rapport à une spécialité médicale
     * -----
     * * Requête préparée
     * @param string $speMedicID        ID de la spécialité médicale
     * @return array                    Liste des ID des events concernés
     */
    public function eventsIdsFromSpeMedicId(string $speMedicID): array
    {
        $stmt = '';
        $stmt = $this->idFinderMapper->maps['MedicEventList']->eventsIdsFromSpeMedicIdStmt();

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':speMedicID', $speMedicID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }

    /** Récupére les ID des events d'un user par rapport à un cabinet médical
     * -----
     * * Requête préparée
     * @param string $docOfficeID       ID du cabinet médical
     * @return array                    Liste des ID d'events concernés
     */
    public function eventsIdsByDocOfficeId(string $docOfficeID): array
    {
        $stmt = '';
        $stmt = $this->idFinderMapper->maps['MedicEventList']->eventsIdsByDocOfficeIdStmt();

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':docOfficeID', $docOfficeID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }

    /** Récupére les ID des events d'un user par rapport à un docteur
     * -----
     * * Requête préparée
     * @param string $medicEventCatID   ID de la catégorie d'events
     * @return array                    Liste des ID d'events concernés
     */
    public function eventsIdsFromOneDocId(string $docID): array
    {
        $stmt = '';
        $stmt = $this->idFinderMapper->maps['MedicEventList']->eventsIdsFromOneDocIdStmt();

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':docID', $docID);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }

    /** Méthode de test pour le développement, récupére un event en particulier
     * @return array         Liste des ID d'events concernés
     */
    public function onlyOneEvent(): array
    {
        $stmt = '';
        $stmt = $this->idFinderMapper->maps['MedicEventList']->eventsIdsFromOneDocIdStmt();

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }
}
