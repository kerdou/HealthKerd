<?php

namespace HealthKerd\Model\common;

abstract class ModelInChief
{
    protected object $pdo; // PDO d'accès à la BDD
    protected object $query; // Contient la requete à envoyer à la base, renvoi une exception en cas de problème


    /** Construction du PDO */
    public function __construct()
    {
        require_once "dbSettings.php"; // fichier de configuration de la connexion à la DB
        $hostmode = 'local';

        // utilise les identifiants de cnx au serveur SQL suivant le mode choisi
        switch ($hostmode) {
            case 'local':
                $host = LOCHOST;
                $base = LOCBASE;
                $user = LOCUSER;
                $password = LOCPASSWORD;
                break;
            case 'remote':
                $host = REMHOST;
                $base = REMBASE;
                $user = REMUSER;
                $password = REMPASSWORD;
        }

        $this->pdoInit($host, $base, $user, $password);
    }


    /** Construction du PDO
     * @param string $host      Adresse de l'hôte
     * @param string $base      Nom de la DB
     * @param string $user      Login de cnx au serveur SQL
     * @param string $password  Mot de passe
     */
    protected function pdoInit(string $host, string $base, string $user, string $password)
    {
        try {
            $this->pdo = new \PDO(
                "mysql:host=" .
                $host . ";" .
                "dbname=" .
                $base,
                $user,
                $password
            );
        } catch (\Exception $e) {
            echo 'Error : ' . $e->getMessage();
            throw $e; // permet d'arrêter le script et d'ajouter l'erreur dans les logs Apache (merci Reno!)
        }
        $this->pdo->exec("SET CHARACTER SET utf8");
    }


    /** Création du string se trouvant derriere le WHERE du stmt
     * * S'il y a plus d'une clé à chercher, on crée les 'columnName = id' necessaires et on les rassemble entre des OR
     * * S'il n'y a qu'une seule clé, on crée un unique 'columnName = id'
     * @param  array  $idList       Liste des clés à inspecter
     * @param  string $idColumnName Nom de la colonne à chercher dans la table
     * @return string               Renvoie du string des 'columnName = id' rassemblés
    */
    protected function stmtWhereBuilder(array $idList, string $idColumnName)
    {
        $whereString = '';
        $whereStringBuildUpArray = array();

        if (count($idList) > 1) {
            foreach ($idList as $id) {
                array_push($whereStringBuildUpArray, ' ' . $idColumnName . ' = ' . $id);
            }
            $whereString = implode(' OR', $whereStringBuildUpArray);
        } elseif (count($idList) == 1) {
            $whereString = ' ' . $idColumnName . ' = ' . $idList[0];
        }

        return $whereString;
    }


    /** Execution de SELECT préparé, 2 modes de Fetch possible suivante le nombre de résultats attendu
     * @param string $fetchMode Défini le type de Fetch: single ou multi
     * @return array Données renvoyées par la DB
    */
    protected function pdoPreparedSelectExecute(string $fetchMode)
    {
        try {
            $this->query->execute();
            $result = array();

            switch ($fetchMode) {
                case 'single':
                    $result = $this->query->fetch(\PDO::FETCH_ASSOC);
                    break;
                case 'multi':
                    $result = $this->query->fetchAll(\PDO::FETCH_ASSOC);
            }

            $this->query->closeCursor();
            return $result;
        } catch (\Exception $queryError) {
            echo 'Error : ' . $queryError->getMessage();
            $this->query->closeCursor();
            throw $queryError; // permet d'arrêter le script et d'ajouter l'erreur dans les logs Apache (merci Reno!)
        }
    }



    /** Execution de SELECT non préparé, 2 modes de Fetch possible suivante le nombre de résultats attendu
     * @param string $stmt      Requete envoyée
     * @param string $fetchMode Défini le type de Fetch: single ou multi
     * @return array Données renvoyées par la DB
     */
    protected function pdoRawSelectExecute(string $stmt, string $fetchMode)
    {
        try {
            $result = $this->pdo->query($stmt);

            switch ($fetchMode) {
                case 'single':
                    $result = $result->fetch(\PDO::FETCH_ASSOC);
                    break;
                case 'multi':
                    $result = $result->fetchAll(\PDO::FETCH_ASSOC);
            }

            return $result;
        } catch (\Exception $queryError) {
            echo 'Error : ' . $queryError->getMessage();
            throw $queryError; // permet d'arrêter le script et d'ajouter l'erreur dans les logs Apache (merci Reno!)
        }
    }


    /** Connexion à la DB, reception des données et séparation avant renvoie par le return
     * @param   object  $pdo        Objet de connexion à la DB
     * @param   string  $stmt       Déclaration unifiée envoyée à la DB
     * @param   array   $dest       Array permettant de connaitre le nombre de réponses attendues
     * @return  array   $resulArray Contient toutes les réponses séparées     *
     */
    protected function pdoEventSelectMultiQuery(string $stmt, array $dest)
    {
        $rawResult = $this->pdo->query($stmt);
        $resultsArray = array();

        // séparation des réponses et placement dans $resultsArray
        // $dest permet uniquement de connaitre le nombre de requetes à traiter
        foreach ($dest as $destKey => $destValue) {
            $fetchedResult = $rawResult->fetchAll(\PDO::FETCH_ASSOC);
            array_push($resultsArray, $fetchedResult);
            $rawResult->nextRowset();
        }
        return $resultsArray;
    }


    /** Envoi de la requete à la BDD puis fermeture de la connexion après qu'elle ait réussi
     * Ne doit être utilisé que pour les INSERT, UPDATE et DELETE préparés
    */
    protected function pdoPreparedInsertUpdateDeleteExecute()
    {
        try {
            $this->query->execute();
        } catch (\Exception $e) {
            echo 'Error : ' . $e->getMessage();
            throw $e; // permet d'arrêter le script et d'ajouter l'erreur dans les logs Apache (merci Reno!)
        }

        $this->query->closeCursor();
    }
}
