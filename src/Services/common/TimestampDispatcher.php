<?php

namespace HealthKerd\Services\common;

/** Classe de dispatch d'élements
 */
class TimestampDispatcher
{
    /** Dispatch d'items par rapport au timestamp de NOW() répartis dans 2 arrays différents
     * @param array $itemsList      Liste des éléments à dispatcher
     * @return array                Eléments dispatcher entre $dispatchedItems['past'] et $dispatchedItems['future']
    */
    public function nowTimestampDispatcher(array $itemsList): array
    {
        $dispatchedItems['past'] = array();
        $dispatchedItems['future'] = array();

        foreach ($itemsList as $key => $value) {
            if ($value['time']['timestamp'] < $_ENV['DATEANDTIME']['nowDate']['nowTimestamp']) {
                array_push($dispatchedItems['past'], $value);
            } else {
                array_push($dispatchedItems['future'], $value);
            }
        }

        return $dispatchedItems;
    }
}
