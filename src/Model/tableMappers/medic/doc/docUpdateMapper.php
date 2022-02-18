<?php

namespace HealthKerd\Model\tableMappers\medic\doc;

/** Mapping d'accés aux templates Update des docteurs
*/
class DocUpdateMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Modification d'un docteur
     */
    public function displayAllDocsListMapper(): void
    {
        $this->maps['UpdateDocList'] = new \HealthKerd\Model\sqlStmtStore\docList\UpdateDocList();
    }
}
