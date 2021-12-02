<?php

namespace HealthKerd\Model\medic\eventDataGatherer;

abstract class EventDataGathererPdoManager extends \HealthKerd\Model\common\ModelInChief
{
    protected array $pdoBufferArray = array(); // Stock tampon des requetes PDO, de la destination de leur contenu dans $dataStore et du contenu retourné par la DB

    public function __construct()
    {
        parent::__construct();
        $this->pdoBufferArrayReinit($this->pdoBufferArray); // Reset des données de $pdoBufferArray
    }


    public function __destruct()
    {
    }

    /** Remise de $pdoBufferArray à son état initial
     */
    protected function pdoBufferArrayReinit()
    {
        $this->pdoBufferArray = array();
        $this->pdoBufferArray = [
            'elements' => array(),
            'joinedStmt' => '',
            'dest' => array(),
            'pdoMixedResult' => array()
        ];
    }



    /** Insertion des déclarations et de leur emplacement de destination
     * @param string $stmt Déclaration pour le PDO, placés dans pdoBufferArray['elements']
     * @param string $dest Emplacement des données à renvoyer dans le $dataStore pdoBufferArray['elements']
     */
    protected function pdoStmtAndDestInsertionInCue(string $stmt, string $dest)
    {
        $temp = ['stmt' => $stmt, 'dest' => $dest];
        array_push($this->pdoBufferArray['elements'], $temp); // on insere les données dans $pdoBufferArray['elements'] afin de les traiter
    }


    /** Traitement des déclarations et des emplacements de retour déjà inserées dans $pdoBufferArray['elements']
     * * Tous les stmt stockés dans pdoBufferArray['elements'] sont concaténés dans pdoBufferArray['joinedStmt']
     * * Toutes les dest sont découpées en array et pushées dans pdoBufferArray['dest']
     */
    protected function pdoStmtAndDestProcessing()
    {
        foreach ($this->pdoBufferArray['elements'] as $elementKey => $elementValue) {
            $this->pdoBufferArray['joinedStmt'] .= $elementValue['stmt']; // fusion des déclarations en une seule.
            $destTemp = explode('/', $elementValue['dest']); // éclatement des strings de destinations en array
            array_push($this->pdoBufferArray['dest'], $destTemp);
        }
    }


    /** Lance la connexion à la DB et recoit les données que seront stockées dans $pdoBufferArray['pdoMixedResult']
     * * On commence par concaténer toutes les requetes et séparer les destinations en attente dans $pdoBufferArray avec pdoStmtAndDestProcessing()
     * * On contacte la DB uniquement s'il y a des stmt en attente
     * * Le contact se fait avec pdoEventSelectMultiQuery(), le résultat est stocké dans $pdoBufferArray['pdoMixedResult']
     * * Il y a un concordance de $key entre $pdoBufferArray['dest'] et $pdoBufferArray['pdoMixedResult']
     * * On se sert de cette concordance pour écrire les données au bon endroit dans $dataStore via pdoResultWriter()
     * * Une fois l'écriture dans $dataStore terminée, on vide $pdoBufferArray pour le préparée à la série de requetes suivantes via pdoBufferArrayReinit()
     */
    protected function pdoQueryExec()
    {
        $this->pdoStmtAndDestProcessing(); // Traitement des déclarations et des emplacements de retour déjà inserées dans $pdoBufferArray['elements']

        // la connexion à la DB ne se lance que s'il y a des requetes attendant d'être lancées
        if (strlen($this->pdoBufferArray['joinedStmt']) > 0) {
            $this->pdoBufferArray['pdoMixedResult'] = $this->pdoEventSelectMultiQuery($this->pdoBufferArray['joinedStmt'], $this->pdoBufferArray['dest']);
        }

        $this->pdoResultWriter();
        $this->pdoBufferArrayReinit();
    }


    /** Placement des données à l'endroit voulu dans $dataStore
     */
    protected function pdoResultWriter()
    {
        foreach ($this->pdoBufferArray['dest'] as $destKey => $destValue) {
            switch (count($destValue)) {
                case 0:
                    $this->dataStore['unexpectedDest'] = array();
                    array_push($this->dataStore['unexpectedDest'], $this->pdoBufferArray['pdoMixedResult'][$destKey]);
                    break;

                case 1:
                    $this->dataStore[$destValue[0]] = $this->pdoBufferArray['pdoMixedResult'][$destKey];
                    break;

                case 2:
                    $this->dataStore[$destValue[0]][$destValue[1]] = $this->pdoBufferArray['pdoMixedResult'][$destKey];
                    break;

                case 3:
                    $this->dataStore[$destValue[0]][$destValue[1]][$destValue[2]] = $this->pdoBufferArray['pdoMixedResult'][$destKey];
                    break;

                default:
                    $this->dataStore['unexpectedDest'] = array();
                    array_push($this->dataStore['unexpectedDest'], $this->pdoBufferArray['pdoMixedResult'][$destKey]);
                    break;
            }
        }
    }
}
