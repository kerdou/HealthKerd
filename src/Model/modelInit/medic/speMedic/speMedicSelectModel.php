<?php

namespace HealthKerd\Model\modelInit\medic\speMedic;

class SpeMedicSelectModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récupére les spécialités médicales liées à une liste d'events
     * @return array        Liste des thèmes médicaux demandés
     */
    public function selectSpeMedicUsedByUser(): array
    {
        $mapper = new \HealthKerd\Model\tableMappers\medic\speMedic\SpeMedicSelectMapper();
        $mapper->selectSpeMedicUsedByUserMapper();

        $whereString = $this->stmtWhereBuilder([$_SESSION['userID']], 'doc_list.userID');
        $stmt = '';
        $stmt = $mapper->maps['SelectSpeMedicFullList']->selectSpeMedicUsedByUserStmt($whereString);

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }
}
