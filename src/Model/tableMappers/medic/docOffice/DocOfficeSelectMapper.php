<?php

namespace HealthKerd\Model\tableMappers\medic\docOffice;

/** Mapping d'accés aux templates Select de spécialités médicales
*/
class DocOfficeSelectMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Récupération des spécialités médicales utilisées par un user
     */
    public function gatherAllDocOfficesMapper(): void
    {
        $this->maps['SelectDocOffice'] = new \HealthKerd\Model\sqlStmtStore\docOfficeList\SelectDocOfficeList();
        $this->maps['SelectDocofficeSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docofficeSpemedicRelation\SelectDocofficeSpemedicRelation();
    }
}
