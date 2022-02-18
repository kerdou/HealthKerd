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

    /** Suppression automatique d'un cabinet médical et de spécialités médicales suite à la suppression d'un docteur
     */
    public function deleteOfficeAndSpeMedicMapper(): void
    {
        $this->maps['DeleteDocDocofficeRelation'] = new \HealthKerd\Model\sqlStmtStore\docDocofficeRelation\DeleteDocDocofficeRelation();
        $this->maps['DeleteDocSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docSpemedicRelation\DeleteDocSpemedicRelation();
    }
}
