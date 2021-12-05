<?php

namespace HealthKerd\Controller\medic\docOffice;

/** Controleur de la section 'accueil' */
abstract class DocOfficeCommonController
{
    protected object $docOfficeModel;

    public function __construct()
    {
        $this->docOfficeModel = new \HealthKerd\Model\medic\docOffice\DocOfficeModel();
    }

    public function __destruct()
    {
    }
}
