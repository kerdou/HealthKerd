<?php

namespace HealthKerd\Controller\medic\docOffice;

/** Classe regroupant les attributs et méthodes commun aux contrôleurs GET et POST des cabinets médicaux
 */
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
