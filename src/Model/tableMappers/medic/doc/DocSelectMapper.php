<?php

namespace HealthKerd\Model\tableMappers\medic\doc;

/** Mapping d'accés aux templates Select des docteurs
*/
class DocSelectMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Récupération des spécialités médicales utilisées par un user
     */
    public function displayAllDocsListMapper(): void
    {
        $this->maps['SelectDocList'] = new \HealthKerd\Model\sqlStmtStore\docList\SelectDocList();
        $this->maps['SelectDocSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docSpemedicRelation\SelectDocSpemedicRelation();
    }

    /** Récupération des données de la page d'informations concernant un docteur
     */
    public function getDataForOneDocPageMapper(): void
    {
        $this->maps['SelectDocList'] = new \HealthKerd\Model\sqlStmtStore\docList\SelectDocList();
        $this->maps['SelectDocSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docSpemedicRelation\SelectDocSpemedicRelation();
        $this->maps['SelectDocDocofficeRelation'] = new \HealthKerd\Model\sqlStmtStore\docDocofficeRelation\SelectDocDocofficeRelation();
        $this->maps['SelectDocofficeSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docofficeSpemedicRelation\SelectDocofficeSpemedicRelation();
        $this->maps['SelectMedicEventList'] = new \HealthKerd\Model\sqlStmtStore\medicEventList\SelectMedicEventList();
    }

    /** Récupération de toutes les données d'un docteur pour l'affichage dans un formulaire
     */
    public function getAllDataForOneDocFromDocListMapper(): void
    {
        $this->maps['SelectDocList'] = new \HealthKerd\Model\sqlStmtStore\docList\SelectDocList();
    }

    /** Récupération de l'ID du dernier docteur créé par le user
    */
    public function getNewDocIDMapper(): void
    {
        $this->maps['SelectDocList'] = new \HealthKerd\Model\sqlStmtStore\docList\SelectDocList();
    }

    public function getAJAXDataForSpeMedDocOfficeFormMapper(): void
    {
        $this->maps['SelectSpeMedicFullList'] = new \HealthKerd\Model\sqlStmtStore\speMedicFullList\SelectSpeMedicFullList();
        $this->maps['SelectDocofficeSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docofficeSpemedicRelation\SelectDocofficeSpemedicRelation();
        $this->maps['SelectDocSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docSpemedicRelation\SelectDocSpemedicRelation();
        $this->maps['SelectDocOfficeList'] = new \HealthKerd\Model\sqlStmtStore\docOfficeList\SelectDocOfficeList();
    }
}
