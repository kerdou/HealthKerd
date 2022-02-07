<?php

namespace HealthKerd\Controller\medic\doc;

/** Attributs et méthodes communs aux contrôleurs GET et POST des docteurs
 */
abstract class DocCommonController
{
    protected object $docModel;

    public function __construct()
    {
        $this->docModel = new \HealthKerd\Model\medic\doc\DocModel();
    }

    public function __destruct()
    {
    }
}
