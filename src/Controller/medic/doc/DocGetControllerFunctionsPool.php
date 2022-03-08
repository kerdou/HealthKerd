<?php

namespace HealthKerd\Controller\medic\doc;

/** Depot de fonctions pour alléger le fichier DocGetController
*/
abstract class DocGetControllerFunctionsPool
{
    public function __destruct()
    {
    }

    /** Récapitulatif des nombres et dates d'events passés et futurs
     * @param array $medicEvents        Données des events passés et à venir
     */
    protected function eventsSummaryCreation(array $medicEvents): void
    {
        $pastEventsQty = sizeof($medicEvents['past']);
        $futureEventsQty = sizeof($medicEvents['future']);

        $this->docList['medicEvent']['qty']['past'] = $pastEventsQty;
        $this->docList['medicEvent']['qty']['coming'] = $futureEventsQty;
        $this->docList['medicEvent']['qty']['total'] = $pastEventsQty + $futureEventsQty;

        if ($pastEventsQty > 0) {
            $this->docList['medicEvent']['dates']['first'] = $this->docList['medicEvent']['past'][0]['time']['frenchDate'];

            $this->docList['medicEvent']['dates']['last'] = $this->docList['medicEvent']['past'][$pastEventsQty - 1]['time']['frenchDate'];
        } else {
            $this->docList['medicEvent']['dates']['first'] = 'Aucun';
            $this->docList['medicEvent']['dates']['last'] = 'Aucun';
        }

        if ($futureEventsQty > 0) {
            $this->docList['medicEvent']['dates']['next'] = $this->docList['medicEvent']['future'][0]['time']['frenchDate'];
        } else {
            $this->docList['medicEvent']['dates']['next'] = 'Aucun';
        }
    }
}
