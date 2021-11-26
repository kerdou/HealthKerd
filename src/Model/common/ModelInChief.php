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


    /** Execution de SELECT préparé, 2 modes de Fetch possible suivante le nombre de résultats attendu
     * @param string $fetchMode Défini le type de Fetch: single ou multi
     * @return array Données renvoyées par la DB
    */
    protected function pdoPreparedSelectExecute(string $fetchMode)
    {
        try {
            $this->query->execute();

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
        $query = $this->pdo->query($stmt);
        $resultsArray = array();

        //echo '<pre>';
        //print_r($query);
        //echo '</pre>';

        // séparation des réponses et placement dans $resultsArray
        // $dest permet uniquement de connaitre le nombre de requetes à traiter
        foreach ($dest as $destKey => $destValue) {
            $fetchedResult = $query->fetchAll(\PDO::FETCH_ASSOC);
            //var_dump($fetchedResult);
            array_push($resultsArray, $fetchedResult);
            $query->nextRowset();
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
