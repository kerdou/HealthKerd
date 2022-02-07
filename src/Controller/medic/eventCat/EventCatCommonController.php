<?php

namespace HealthKerd\Controller\medic\eventCat;

/** Classe regroupant les attributs et méthodes communs aux contrôleurs GET et POST des catégories d'events
 */
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
