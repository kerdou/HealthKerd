<?php

namespace HealthKerd\Processor\medic\ordo;

class OrdoVaxOrganizer
{
    private array|null $ordoVaxList = array();
    private array|null $dateAndTime = array();
    private array|null $prescOrdoVax = array();
    private array|null $ordoVaxSlots = array();


    /**
     *
     */
    public function ordoVaxGeneralBuildOrder(
        array|null $ordoVaxList,
        array|null $dateAndTime,
        array|null $prescOrdoVax,
        array|null $ordoVaxSlots
    ) {
        $this->ordoVaxList = $ordoVaxList;
        $this->dateAndTime = $dateAndTime;
        $this->prescOrdoVax = $prescOrdoVax;
        $this->ordoVaxSlots = $ordoVaxSlots;

        $this->ordoVaxContentOrganizer();
        $this->timeManagement();
        $this->prescAdder();
        $this->slotsAdder();

        //echo '<pre>';
        //print_r($this->ordoVaxList);
        //echo '</pre>';

        return $this->ordoVaxList;
    }


    /**
     *
     */
    private function ordoVaxContentOrganizer()
    {
        $ordoTempArray = array();

        foreach ($this->ordoVaxList as $value) {
            $tempArray = array();

            $tempArray['ordoVaxID'] = $value['ordoVaxID'];
            $tempArray['ordoType'] = 'vax';
            $tempArray['diagID'] = $value['diagID'];

            $tempArray['time']['date'] = $value['date'];
            $tempArray['time']['timestamp'] = '';
            $tempArray['time']['frenchDate'] = '';

            $tempArray['comment'] = $value['comment'];

            $tempArray['prescList'] = array();
            $tempArray['slotsList'] = array();

            $tempArray['idStorage']['userID'] = $value['userID'];
            $tempArray['idStorage']['medicEventID'] = $value['medicEventID'];
            $tempArray['idStorage']['docID'] = $value['docID'];
            $tempArray['idStorage']['replacedDocID'] = $value['replacedDocID'];

            array_push($ordoTempArray, $tempArray);
        }

        $this->ordoVaxList = $ordoTempArray;
    }


    /**
     *
     */
    private function timeManagement()
    {
        foreach ($this->ordoVaxList as $key => $value) {
            $dateObj = date_create($value['time']['date'], $this->dateAndTime['timezoneObj']);
            $UTCOffset = date_offset_get($dateObj); // récupération de l'offset de timezone
            $timestamp = date_timestamp_get($dateObj) + $UTCOffset; // on ajout l'écart de timezone au timestamp pour qu'il soit correct
            $this->ordoVaxList[$key]['time']['timestamp'] = $timestamp;
            $this->ordoVaxList[$key]['time']['frenchDate'] = utf8_encode(ucwords(gmstrftime('%A %e %B %Y', $timestamp))); // utf8_encode() pour s'assurer que les accents passent bien
        }
    }


    /**
     *
     */
    private function prescAdder()
    {
        foreach ($this->ordoVaxList as $ordoKey => $ordoValue) {
            foreach ($this->prescOrdoVax as $prescKey => $prescValue) {
                if ($ordoValue['ordoVaxID'] == $prescValue['ordoVaxID']) {
                    array_push($this->ordoVaxList[$ordoKey]['prescList'], $prescValue);
                }
            }
        }
    }

    /**
     *
     */
    private function slotsAdder()
    {
        foreach ($this->ordoVaxList as $ordoKey => $ordoValue) {
            foreach ($this->ordoVaxSlots as $slotsKey => $slotsValue) {
                if ($ordoValue['ordoVaxID'] == $slotsValue['ordoVaxID']) {
                    array_push($this->ordoVaxList[$ordoKey]['slotsList'], $slotsValue);
                }
            }
        }
    }
}
