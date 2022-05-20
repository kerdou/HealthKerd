<?php

namespace HealthKerd\Model\tableMappers\medic\doc;

/** Mapping d'accés aux templates Delete des docteurs
*/
class DocDeleteMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Suppression d'un docteur
     */
    public function deleteDocMapper(): void
    {
        $this->maps['DeleteDocList'] = new \HealthKerd\Model\sqlStmtStore\docList\DeleteDocList();
    }
}
