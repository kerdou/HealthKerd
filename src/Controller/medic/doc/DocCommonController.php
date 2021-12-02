<?php

namespace HealthKerd\Controller\medic\doc;

/** Controleur de la section 'accueil' */
abstract class DocCommonController
{
    protected object $docModel;

    public function __construct()
    {
        $this->docModel = new \HealthKerd\Model\medic\doc\DocModel();
    }


}
