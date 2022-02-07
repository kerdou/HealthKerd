<?php

namespace HealthKerd\Model\login;

/** Model de la section 'login'
*/
class LoginModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __destruct()
    {
    }

    /** Récupération des identifiants dans la base selon le userLogin envoyé par le user
     * @param array $postArray      Contient les paramètres du $_POST
     * @return array                Renvoie les infos du user
     */
    public function checkUserLogs(array $postArray)
    {
        $stmt = "SELECT
                    userID, userPwd, isAdmin, firstName, lastName
                FROM
                    users_list
                WHERE
                    userLogin = :userLogin";
        $this->query = $this->pdo->prepare($stmt);
        $this->query->bindParam(':userLogin', $postArray['userLogin']);

        $result = $this->pdoPreparedSelectExecute('single');
        return $result;
    }
}
