<?php

namespace HealthKerd\Processor\medic\ordo;

class OrdoLaboOrganizer
{
    private array|null $ordoLaboList = array();
    private array|null $dateAndTime = array();
    private array|null $prescOrdoLabo = array();
    private array|null $ordoLaboSlots = array();

    /**
     *
     */
    public function ordoLaboGeneralBuildOrder(
        array|null $ordoLaboList,
        array|null $dateAndTime,
        array|null $prescOrdoLabo,
        array|null $ordoLaboSlots
    ) {
        $this->ordoLaboList = $ordoLaboList;
        $this->dateAndTime = $dateAndTime;
        $this->prescOrdoLabo = $prescOrdoLabo;
        $this->ordoLaboSlots = $ordoLaboSlots;

        $this->ordoLaboContentOrganizer();
        $this->timeManagement();
        $this->prescAdder();
        $this->slotsAdder();

        //var_dump($this->ordoLaboList);
        //var_dump($this->prescOrdoLabo);
        //var_dump($this->ordoLaboSlots);

        //echo '<pre>';
        //print_r($this->ordoLaboList);
        //echo '</pre>';

        return $this->ordoLaboList;
    }

    /**
     *
     */
    private function ordoLaboContentOrganizer()
    {
        $ordoTempArray = array();

        foreach ($this->ordoLaboList as $value) {
            $tempArray = array();

            $tempArray['ordoLaboID'] = $value['ordoLaboID'];
            $tempArray['ordoType'] = 'laboSampling';
            $tempArray['diagID'] = $value['diagID'];

            $tempArray['time']['date'] = $value['date'];
            $tempArray['time']['timestamp'] = '';
            $tempArray['time']['frenchDate'] = '';

            $tempArray['renewal'] = $value['renewal'];
            $tempArray['comment'] = $value['comment'];

            $tempArray['prescList'] = array();
            $tempArray['slotsList'] = array();

            $tempArray['idStorage']['userID'] = $value['userID'];
            $tempArray['idStorage']['medicEventID'] = $value['medicEventID'];
            $tempArray['idStorage']['docID'] = $value['docID'];
            $tempArray['idStorage']['replacedDocID'] = $value['replacedDocID'];

            array_push($ordoTempArray, $tempArray);
        }

        $this->ordoLaboList = $ordoTempArray;
    }

    /**
     *
     */
    private function timeManagement()
    {
        foreach ($this->ordoLaboList as $key => $value) {
            $dateObj = date_create($value['time']['date'], $this->dateAndTime['timezoneObj']);
            $UTCOffset = date_offset_get($dateObj); // récupération de l'offset de timezone
            $timestamp = date_timestamp_get($dateObj) + $UTCOffset; // on ajout l'écart de timezone au timestamp pour qu'il soit correct
            $this->ordoLaboList[$key]['time']['timestamp'] = $timestamp;
            $this->ordoLaboList[$key]['time']['frenchDate'] = utf8_encode(ucwords(gmstrftime('%A %e %B %Y', $timestamp))); // utf8_encode() pour s'assurer que les accents passent bien
        }
    }

    /**
     *
     */
    private function prescAdder()
    {
        foreach ($this->ordoLaboList as $ordoKey => $ordoValue) {
            foreach ($this->prescOrdoLabo as $prescKey => $prescValue) {
                if ($ordoValue['ordoLaboID'] == $prescValue['ordoLaboID']) {
                    array_push($this->ordoLaboList[$ordoKey]['prescList'], $prescValue);
                }
            }
        }
    }

    /**
     *
     */
    private function slotsAdder()
    {
        foreach ($this->ordoLaboList as $ordoKey => $ordoValue) {
            foreach ($this->ordoLaboSlots as $slotsKey => $slotsValue) {
                if ($ordoValue['ordoLaboID'] == $slotsValue['ordoLaboID']) {
                    array_push($this->ordoLaboList[$ordoKey]['slotsList'], $slotsValue);
                }
            }
        }
    }
}
