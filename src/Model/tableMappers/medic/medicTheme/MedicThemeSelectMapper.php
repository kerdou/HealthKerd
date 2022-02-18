<?php

namespace HealthKerd\Model\tableMappers\medic\medicTheme;

/** Mapping d'accés aux templates Select de thème médicaux
*/
class MedicThemeSelectMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Récupération des thèmes médicaux utilisés par un user
     */
    public function selectMedicThemeByUserIdMapper(): void
    {
        $this->maps['SelectMedicThemesList'] = new \HealthKerd\Model\sqlStmtStore\medicThemeList\SelectMedicThemesList();
    }
}
