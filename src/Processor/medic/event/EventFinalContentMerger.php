<?php

namespace HealthKerd\Processor\medic\event;

/** Assemblage de tous les éléments de chaque event puis dispatch suivant qu'ils soient dans le passé ou à venir
 */
class EventFinalContentMerger
{
    private array $eventArray = array();
    private int $todayEarlyTimestamp = 0;

    private array $diagList = array();
    private array $careSessions = array();
    private array $vaxSessions = array();

    private array $futureEvents = array();
    private array $pastEvents = array();
    private array $timeSortedEvents = array();


    public function __destruct()
    {
    }

    /** Assemblage de tous les éléments de chaque event puis dispatch dans $pastEvents ou $futureEvents
     * @param array $eventArray         Liste des événements déjà assemblés
     * @param int $todayEarlyTimestamp  Timestamp du début de journée
     * @param array $diagList           Liste des diagnostics déjà assemblés
     * @param array $careSessions       Liste des sessions de soin déjà assemblées
     * @param array $vaxSessions        Liste des sessions de vaccination déjà assemblées
     * @return array $timeSortedEvents  Toutes les données assemblées dans chaque event
     */
    public function eventContentMerger(
        array $eventArray,
        int $todayEarlyTimestamp,
        array $diagList,
        array $careSessions,
        array $vaxSessions
    ) {
        $this->eventArray = $eventArray;
        $this->todayEarlyTimestamp = $todayEarlyTimestamp;
        $this->diagList = $diagList;
        $this->careSessions = $careSessions;
        $this->vaxSessions = $vaxSessions;

        $this->diagContentMerger();
        $this->careSessionsContentMerger();
        $this->vaxSessionsContentMerger();

        $this->eventTimeDispatcher();
        $this->pastAndFutureEventsTimeSorting();

        $this->timeSortedEvents['pastEvents'] = $this->pastEvents;
        $this->timeSortedEvents['futureEvents'] = $this->futureEvents;

        //echo '<pre>';
        //print_r($this->eventArray);
        //print_r($this->pastEvents);
        //print_r($this->futureEvents);
        //print_r($this->timeSortedEvents);
        //echo '</pre>';

        return $this->timeSortedEvents;
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

    /** Tri des events dans 2 arrays par rapport à leur timestamp
     * Les events ayant un timestamp inférieur à celui de ce matin à 00:00 iront dans $passEvents
     * Les events ayant un timestamp supérieur ou égal  celui de ce matin à 00:00 iront dans ^futureEvents
     */
    private function eventTimeDispatcher()
    {
        foreach ($this->eventArray as $key => $value) {
            if ($value['time']['timestamp'] < $this->todayEarlyTimestamp) {
                array_push($this->pastEvents, $value);
            } elseif ($value['time']['timestamp'] >= $this->todayEarlyTimestamp) {
                array_push($this->futureEvents, $value);
            }
        }
    }

    /** Tri des events par date et heure une fois qu'ils ont déjà été dispatchés dans $pastEvents et $futureEvents
     * * Les $pastEvents sont triés par ordre décroissant de timestamp
     * * Les $futureEvents sont triés par ordre croissant de timestamp
     */
    private function pastAndFutureEventsTimeSorting()
    {
        uasort($this->pastEvents, array($this, "decrTimestampEventSorting"));
        uasort($this->futureEvents, array($this, "incrTimestampEventSorting"));
    }

    /** Tri des events en ordre croissant par leurs timestamps
     * Lancé depuis pastAndFutureEventsTimeSorting()
     * @param array $firstValue     Premier timestamp à comparer
     * @param array $secondValue    Second timestamp à comparer
     * @return int                  Integer indiquant 0 si les 2 valeurs sont égales, -1 si $firstValue est plus petit, 1 si $firstValue est plus grand
    */
    private function incrTimestampEventSorting(array $firstValue, array $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] < $secondValue['time']['timestamp']) ? -1 : 1;
    }

    /** Tri des events en ordre décroissant par leurs timestamps
     * Lancé depuis pastAndFutureEventsTimeSorting()
     * @param array $firstValue     Premier timestamp à comparer
     * @param array $secondValue    Second timestamp à comparer
     * @return int                  Integer indiquant 0 si les 2 valeurs sont égales, -1 si $firstValue est plus grand, 1 si $firstValue est plus petit
    */
    private function decrTimestampEventSorting($firstValue, $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] > $secondValue['time']['timestamp']) ? -1 : 1;
    }
}
