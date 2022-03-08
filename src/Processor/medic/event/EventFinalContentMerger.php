<?php

namespace HealthKerd\Processor\medic\event;

/** Assemblage de tous les éléments de chaque event puis dispatch suivant qu'ils soient dans le passé ou à venir
 */
class EventFinalContentMerger
{
    private array $eventArray = array();
    private array $diagList = array();
    private array $careSessions = array();
    private array $vaxSessions = array();

    public function __destruct()
    {
    }

    /** Assemblage de tous les éléments de chaque event puis dispatch dans $pastEvents ou $futureEvents
     * @param array $eventArray         Liste des événements déjà assemblés
     * @param array $diagList           Liste des diagnostics déjà assemblés
     * @param array $careSessions       Liste des sessions de soin déjà assemblées
     * @param array $vaxSessions        Liste des sessions de vaccination déjà assemblées
     * @return array $timeSortedEvents  Toutes les données assemblées dans chaque event
     */
    public function eventContentMerger(
        array $eventArray,
        array $diagList,
        array $careSessions,
        array $vaxSessions
    ): array {
        $this->eventArray = $eventArray;
        $this->diagList = $diagList;
        $this->careSessions = $careSessions;
        $this->vaxSessions = $vaxSessions;

        $this->diagContentMerger();
        $this->careSessionsContentMerger();
        $this->vaxSessionsContentMerger();

        $timestampDispatcher = new \HealthKerd\Services\common\TimestampDispatcher();
        $dispatchedEvents = $timestampDispatcher->timestampDispatcher($this->eventArray);

        $timestampSorter = new \HealthKerd\Services\common\TimestampSorting();
        $timeSortedEvents['pastEvents'] = $timestampSorter->decrTimestampSortLauncher($dispatchedEvents['past']);
        $timeSortedEvents['futureEvents'] = $timestampSorter->incrTimestampSortLauncher($dispatchedEvents['future']);

        return $timeSortedEvents;
    }

    /** Ajout des diagnostics complétement assemblés au sein des events
     */
    private function diagContentMerger()
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($this->diagList as $diagKey => $diagValue) {
                if ($eventValue['medicEventID'] == $diagValue['medicEventID']) {
                    $this->eventArray[$eventKey]['content']['diag'] = $diagValue;
                }
            }
        }
    }

    /** Ajout des sessions de soin complétement assemblées au sein des events
     */
    private function careSessionsContentMerger()
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($this->careSessions as $careSessKey => $careSessValue) {
                if ($eventValue['medicEventID'] == $careSessValue['medicEventID']) {
                    $this->eventArray[$eventKey]['content']['careSession'] = $careSessValue;
                }
            }
        }
    }

    /** Ajout des sessions de vaccination complétement assemblées au sein des events
     */
    private function vaxSessionsContentMerger()
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($this->vaxSessions as $vaxSessKey => $vaxSessValue) {
                if ($eventValue['medicEventID'] == $vaxSessValue['medicEventID']) {
                    $this->eventArray[$eventKey]['content']['vaxSession'] = $vaxSessValue;
                }
            }
        }
    }
}
