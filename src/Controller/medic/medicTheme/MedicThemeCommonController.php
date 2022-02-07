<?php

namespace HealthKerd\Controller\medic\medicTheme;

/** Classe regroupant les attributs et méthodes communs aux contrôleurs GET et POST des thémes médicaux
 */
abstract class MedicThemeCommonController
{
    protected object $medicThemeModel;

    public function __construct()
    {
        $this->medicThemeModel = new \HealthKerd\Model\medic\medicTheme\MedicThemeModel();
    }

    public function __destruct()
    {
    }
}
