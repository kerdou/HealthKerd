<?php

namespace HealthKerd\Processor\medic\event;

class EventFinalContentMerger
{

    private array|null $eventArray = array();
    private int $todayEarlyTimestamp = 0;

    private array|null $diagList = array();
    private array|null $careSessions = array();
    private array|null $vaxSessions = array();

    private array|null $futureEvents = array();
    private array|null $pastEvents = array();
    private array|null $timeSortedEvents = array();

    /**
     *
     */
    public function eventContentMerger(
        array|null $eventArray,
        int $todayEarlyTimestamp,
        array|null $diagList,
        array|null $careSessions,
        array|null $vaxSessions
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

        $this->timeSortedEvents['containsEvents'] = false;
        $this->timeSortedEvents['pastEvents'] = $this->pastEvents;
        $this->timeSortedEvents['futureEvents'] = $this->futureEvents;


        if (
            sizeof($this->timeSortedEvents['pastEvents']) > 0 ||
            sizeof($this->timeSortedEvents['futureEvents']) > 0
        ) {
            $this->timeSortedEvents['containsEvents'] = true;
        } else {
            $this->timeSortedEvents['containsEvents'] = false;
        }

        //echo '<pre>';
        //print_r($this->eventArray);
        //print_r($this->pastEvents);
        //print_r($this->futureEvents);
        //print_r($this->timeSortedEvents);
        //echo '</pre>';

        return $this->timeSortedEvents;
    }


    /**
     *
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


    /**
     *
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


    /**
     *
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


    /** Tri des events dans 2 arrays par rapport à leur timestamp */
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


    /**
     *
     */
    private function pastAndFutureEventsTimeSorting()
    {
        uasort($this->pastEvents, array($this, "decrTimestampEventSorting"));
        uasort($this->futureEvents, array($this, "incrTimestampEventSorting"));
    }


    /** Tri des ordonnances en ordre croissant par timestamp */
    private function incrTimestampEventSorting($firstValue, $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] < $secondValue['time']['timestamp']) ? -1 : 1;
    }


    /** Tri des ordonnances en ordre décroissant par timestamp */
    private function decrTimestampEventSorting($firstValue, $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] > $secondValue['time']['timestamp']) ? -1 : 1;
    }
}
