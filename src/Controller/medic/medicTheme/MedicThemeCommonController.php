<?php

namespace HealthKerd\Controller\medic\medicTheme;

/** Controleur de la section 'accueil' */
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
