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
}
