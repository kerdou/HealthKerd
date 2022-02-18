<?php

namespace HealthKerd\Model\tableMappers\medic\eventsFinderAndGatherer;

/** Stockage des templates de requêtes SQL dédiées au Select d'events médicaux
*/
class EventIdFinderSelectMapper
{
    public array $maps = array();

    public function __destruct()
    {
    }

    public function medicEventListMapping()
    {
        $this->maps['MedicEventList'] = new \HealthKerd\Model\sqlStmtStore\medicEventList\SelectMedicEventList();
    }
}
