<?php

namespace HealthKerd\Model\modelInit\userAccount;

class UserUpdateModel extends \HealthKerd\Model\common\PdoBufferManager
{
    private object $mapper;

    public function __construct()
    {
        parent::__construct();
        $this->mapper = new \HealthKerd\Model\tableMappers\userAccount\UserAccountUpdateMapper();
    }

    public function __destruct()
    {
    }

    /** Mise à jour des données d'un user
     * @param array $cleanedUpPost      Données nettoyées et vérifiée du user à modifier
     * @return string                   Message d'erreur s'il y en a eu
     */
    public function updateUserAccountData(array $cleanedUpPost): string
    {
        $this->mapper->updateUserAccountDataMapper();
        $userStmt = $this->mapper->maps['UpdateUsersList']->updateUserDataStmt();

        $this->query = $this->pdo->prepare($userStmt);
        $this->query->bindParam(':userLogin', $cleanedUpPost['login']);
        $this->query->bindParam(':email', $cleanedUpPost['mail']);
        $this->query->bindParam(':firstName', $cleanedUpPost['firstname']);
        $this->query->bindParam(':lastName', $cleanedUpPost['lastname']);
        $this->query->bindParam(':birthDate', $cleanedUpPost['birthDate']);
        $this->query->bindParam(':gender', $cleanedUpPost['gender']);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $pdoErrorMessage = '';
        $pdoErrorMessage = $this->pdoPreparedInsertUpdateDeleteExecute();

        return $pdoErrorMessage;
    }

    /** Modification du mot de passe
     * @param string $hash      Hash du nouveau mot de passe
     * @return string           Message d'erreur s'il y en a eu
     */
    public function updateUserPwd(string $hash): string
    {
        $this->mapper->updateUserPwdMapper();
        $userStmt = $this->mapper->maps['UpdateUsersList']->updateUserPwdStmt();

        $this->query = $this->pdo->prepare($userStmt);
        $this->query->bindParam(':userPwd', $hash);
        $this->query->bindParam(':userID', $_SESSION['userID']);

        $pdoErrorMessage = '';
        $pdoErrorMessage = $this->pdoPreparedInsertUpdateDeleteExecute();

        return $pdoErrorMessage;
    }
}
