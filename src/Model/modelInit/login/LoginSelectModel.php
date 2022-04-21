<?php

namespace HealthKerd\Model\modelInit\login;

/** Model de la section 'login'
*/
class LoginSelectModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    /** Récupération des identifiants dans la base selon le userLogin envoyé par le user
     * @param array $postArray      Contient les paramètres du $_POST
     * @return array                Renvoie les infos du user
     */
    public function checkUserLogs(array $postArray): array
    {

        $mapper = new \HealthKerd\Model\tableMappers\login\LoginSelectMapper();
        $mapper->checkUserLogsMapping();

        $this->pdo->beginTransaction();

        // comptage du nombre de comptes
        $countStmt = $mapper->loginSelectMaps['SelectUserList']->accountCountingStmt();
        $this->query = $this->pdo->prepare($countStmt);
        $this->query->bindParam(':userLogin', $postArray['userLogin']);
        $countResult = $this->pdoPreparedSelectExecute('single');

        // récupération des données du user
        $logsStmt = $mapper->loginSelectMaps['SelectUserList']->checkUserLogsStmt();
        $this->query = $this->pdo->prepare($logsStmt);
        $this->query->bindParam(':userLogin', $postArray['userLogin']);
        $logsResult = $this->pdoPreparedSelectExecute('single');

        $this->pdo->commit(); // execution de la requete

        return ['countResult' => $countResult['COUNT(userLogin)'], 'logsResult' => $logsResult] ;
    }
}
