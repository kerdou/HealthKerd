<?php

namespace HealthKerd\Model\tableMappers\medic\eventCat;

/** Stockage des templates de requêtes SQL dédiées au Select d'events médicaux
*/
class EventCatSelectMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    /** Récuperer les données de toutes les catégories d'events utilisées par un user
     */
    public function gatherAllEventsCatsMapping(): void
    {
        $this->maps['SelectMedicEventCategory'] = new \HealthKerd\Model\sqlStmtStore\medicEventCategory\SelectMedicEventCategory();
    }
}
