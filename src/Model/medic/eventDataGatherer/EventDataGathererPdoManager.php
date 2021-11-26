<?php

namespace HealthKerd\Model\medic\eventDataGatherer;

abstract class EventDataGathererPdoManager extends \HealthKerd\Model\common\ModelInChief
{
    protected array $pdoArray = array(); // Stock tampon des requetes PDO, de la destination de leur contenu dans $dataStore et du contenu retourné par la DB

    public function __construct()
    {
        parent::__construct();
        $this->pdoArrayReinit($this->pdoArray); // Reset des données de $pdoArray
    }


    /** Remise de $pdoArray à son état initial
     */
    protected function pdoArrayReinit()
    {
        $this->pdoArray = array();
        $this->pdoArray = [
            'elements' => array(),
            'joinedStmt' => '',
            'dest' => array(),
            'pdoMixedResult' => array()
        ];
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


    /** Insertion des déclarations et de leur emplacement de destination
     * @param string $stmt Déclaration pour le PDO, placés dans pdoArray['elements']
     * @param string $dest Emplacement des données à renvoyer dans le $dataStore pdoArray['elements']
     */
    protected function pdoStmtAndDestInsertionInCue(string $stmt, string $dest)
    {
        $temp = ['stmt' => $stmt, 'dest' => $dest];
        array_push($this->pdoArray['elements'], $temp); // on insere les données dans $pdoArray['elements'] afin de les traiter
    }



    /** Lance la connexion à la DB et recoit les données que seront stockées dans $pdoArray['pdoMixedResult']
     * * On commence par concaténer toutes les requetes et séparer les destinations en attente dans $pdoArray avec pdoStmtAndDestProcessing()
     * * On contacte la DB uniquement s'il y a des stmt en attente
     * * Le contact se fait avec pdoEventSelectMultiQuery(), le résultat est stocké dans $pdoArray['pdoMixedResult']
     * * Il y a un concordance de $key entre $pdoArray['dest'] et $pdoArray['pdoMixedResult']
     * * On se sert de cette concordance pour écrire les données au bon endroit dans $dataStore via pdoResultWriter()
     * * Une fois l'écriture dans $dataStore terminée, on vide $pdoArray pour le préparée à la série de requetes suivantes via pdoArrayReinit()
     */
    protected function pdoQueryExec()
    {
        $this->pdoStmtAndDestProcessing(); // Traitement des déclarations et des emplacements de retour déjà inserées dans $pdoArray['elements']

        // la connexion à la DB ne se lance que s'il y a des requetes attendant d'être lancées
        if (strlen($this->pdoArray['joinedStmt']) > 0) {
            $this->pdoArray['pdoMixedResult'] = $this->pdoEventSelectMultiQuery($this->pdoArray['joinedStmt'], $this->pdoArray['dest']);
        }

        $this->pdoResultWriter();
        $this->pdoArrayReinit();
    }


    /** Traitement des déclarations et des emplacements de retour déjà inserées dans $pdoArray['elements']
     * * Tous les stmt stockés dans pdoArray['elements'] sont concaténés dans pdoArray['joinedStmt']
     * * Toutes les dest sont découpées en array et pushées dans pdoArray['dest']
     */
    protected function pdoStmtAndDestProcessing()
    {
        foreach ($this->pdoArray['elements'] as $elementKey => $elementValue) {
            $this->pdoArray['joinedStmt'] .= $elementValue['stmt']; // fusion des déclarations en une seule.
            $destTemp = explode('/', $elementValue['dest']); // éclatement des strings de destinations en array
            array_push($this->pdoArray['dest'], $destTemp);
        }
    }


    /** Placement des données à l'endroit voulu dans $dataStore
     */
    protected function pdoResultWriter()
    {
        foreach ($this->pdoArray['dest'] as $destKey => $destValue) {
            switch (count($destValue)) {
                case 0:
                    $this->dataStore['unexpectedDest'] = array();
                    array_push($this->dataStore['unexpectedDest'], $this->pdoArray['pdoMixedResult'][$destKey]);
                    break;

                case 1:
                    $this->dataStore[$destValue[0]] = $this->pdoArray['pdoMixedResult'][$destKey];
                    break;

                case 2:
                    $this->dataStore[$destValue[0]][$destValue[1]] = $this->pdoArray['pdoMixedResult'][$destKey];
                    break;

                case 3:
                    $this->dataStore[$destValue[0]][$destValue[1]][$destValue[2]] = $this->pdoArray['pdoMixedResult'][$destKey];
                    break;

                default:
                    $this->dataStore['unexpectedDest'] = array();
                    array_push($this->dataStore['unexpectedDest'], $this->pdoArray['pdoMixedResult'][$destKey]);
                    break;
            }
        }
    }
}
