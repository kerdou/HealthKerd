<?php

namespace HealthKerd\Model\tableMappers\medic\doc;

/** Mapping d'accés aux templates Insert des docteurs
*/
class DocInsertMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Ajout d'un docteur
     */
    public function addDocMapper(): void
    {
        $this->maps['InsertDocList'] = new \HealthKerd\Model\sqlStmtStore\docList\InsertDocList();
    }

    /** Ajout automatique d'un cabinet médical et de spécialités médicales suite à la création d'un docteur
     */
    public function addOfficeAndSpeMedicMapper(): void
    {
        $this->maps['InsertDocDocofficeRelation'] = new \HealthKerd\Model\sqlStmtStore\docDocofficeRelation\InsertDocDocofficeRelation();
        $this->maps['InsertDocSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docSpemedicRelation\InsertDocSpemedicRelation();
    }
}
