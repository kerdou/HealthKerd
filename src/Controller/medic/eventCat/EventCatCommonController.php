<?php

namespace HealthKerd\Controller\medic\eventCat;

/** Controleur de la section 'accueil' */
abstract class EventCatCommonController
{
    protected object $eventCatModel;

    public function __construct()
    {
        $this->eventCatModel = new \HealthKerd\Model\medic\eventCat\EventCatModel();
    }

    public function __destruct()
    {
    }
}
