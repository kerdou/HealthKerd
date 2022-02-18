<?php

namespace HealthKerd\Model\tableMappers\medic\speMedic;

/** Mapping d'accés aux templates Select de spécialités médicales
*/
class SpeMedicSelectMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Récupération des spécialités médicales utilisées par un user
     */
    public function selectSpeMedicUsedByUserMapper(): void
    {
        $this->maps['SelectSpeMedicFullList'] = new \HealthKerd\Model\sqlStmtStore\speMedicFullList\SelectSpeMedicFullList();
    }
}
