<?php

namespace HealthKerd\Services\common;

/** Classe de dispatch d'élements
 */
class TimestampDispatcher
{
    /** Dispatch d'items par rapport à leur timestamp réparti dans 2 arrays différents
     * @param array $itemsList      Liste des éléments à dispatcher
     * @return array                Eléments dispatcher entre $dispatchedItems['past'] et $dispatchedItems['future']
    */
    public function timestampDispatcher(array $itemsList): array
    {
        $dispatchedItems['past'] = array();
        $dispatchedItems['future'] = array();

        foreach ($itemsList as $key => $value) {
            if ($value['time']['timestamp'] < $_ENV['DATEANDTIME']['todayData']['earlyTimestamp']) {
                array_push($dispatchedItems['past'], $value);
            } elseif ($value['time']['timestamp'] >= $_ENV['DATEANDTIME']['todayData']['lateTimestamp']) {
                array_push($dispatchedItems['future'], $value);
            }
        }

        return $dispatchedItems;
    }
}
