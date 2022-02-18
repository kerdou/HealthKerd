<?php

namespace HealthKerd\Model\modelInit\medic\medicTheme;

/** Classe dédiée à la recherche de thèmes médicaux
 */
class MedicThemeModel extends \HealthKerd\Model\common\ModelInChief
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }

    public function selectMedicThemeByUserId()
    {
        $mapper = new \HealthKerd\Model\tableMappers\medic\medicTheme\MedicThemeSelectMapper();
        $mapper->selectMedicThemeByUserIdMapper();

        $stmt = '';
        $stmt = $mapper->maps['SelectMedicThemesList']->selectMedicThemeByUserIdStmt();

        $result = $this->pdoRawSelectExecute($stmt, 'multi');
        return $result;
    }
}
