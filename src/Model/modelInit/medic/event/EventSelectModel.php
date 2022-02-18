<?php

namespace HealthKerd\Model\medic\event;

/** Classe de récupération du contenu des events suivant les eventID demandés */
class EventSelectModel extends \HealthKerd\Model\common\PdoBufferManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }
}
