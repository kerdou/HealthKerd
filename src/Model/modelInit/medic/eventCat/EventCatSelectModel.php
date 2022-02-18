<?php

namespace HealthKerd\Model\modelInit\medic\eventCat;

/** Model SELECT des catégories d'events
 */
class EventCatSelectModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récuperer les données de toutes les catégories d'events utilisées par un user
     * -----
     * * Requête préparée
     * @return array        Liste des infos de toutes les spécialités médicales concernées
     */
    public function gatherAllEventsCats(): array
    {
        $mapper = new \HealthKerd\Model\tableMappers\medic\eventCat\EventCatSelectMapper();
        $mapper->gatherAllEventsCatsMapping();

        $stmt = "";
        $stmt = $mapper->maps['SelectMedicEventCategory']->gatherAllEventsCatsStmt();

        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $result = $this->pdoPreparedSelectExecute('multi');
        return $result;
    }
}
