<?php

namespace HealthKerd\Processor\medic\ordo;

class OrdoPharmaOrganizer
{
    private array|null $ordoPharmaList = array();
    private array|null $dateAndTime = array();
    private array|null $prescOrdoPharma = array();

    /**
     *
     */
    public function ordoPharmaGeneralBuildOrder(
        array|null $ordoPharmaList,
        array|null $dateAndTime,
        array|null $prescOrdoPharma
    ) {
        $this->ordoPharmaList = $ordoPharmaList;
        $this->dateAndTime = $dateAndTime;
        $this->prescOrdoPharma = $prescOrdoPharma;

        $this->ordoPharmaContentOrganizer();
        $this->timeManagement();
        $this->prescAdder();

        //echo '<pre>';
        //print_r($this->ordoPharmaList);
        //echo '</pre>';

        return $this->ordoPharmaList;
    }

    /**
     *
     */
    private function ordoPharmaContentOrganizer()
    {
        $ordoTempArray = array();

        foreach ($this->ordoPharmaList as $value) {
            $tempArray = array();

            $tempArray['ordoPharmaID'] = $value['ordoPharmaID'];
            $tempArray['ordoType'] = 'pharma';
            $tempArray['diagID'] = $value['diagID'];

            $tempArray['time']['date'] = $value['ordoDate'];
            $tempArray['time']['timestamp'] = '';
            $tempArray['time']['frenchDate'] = '';

            $tempArray['comment'] = $value['ordoComment'];

            $tempArray['prescList'] = array();

            $tempArray['idStorage']['userID'] = $value['userID'];
            $tempArray['idStorage']['medicEventID'] = $value['medicEventID'];
            $tempArray['idStorage']['docID'] = $value['docID'];
            $tempArray['idStorage']['replacedDocID'] = $value['replacedDocID'];

            array_push($ordoTempArray, $tempArray);
        }

        $this->ordoPharmaList = $ordoTempArray;
    }

    /**
     *
     */
    private function timeManagement()
    {
        foreach ($this->ordoPharmaList as $key => $value) {
            $dateObj = date_create($value['time']['date'], $this->dateAndTime['timezoneObj']);
            $UTCOffset = date_offset_get($dateObj); // récupération de l'offset de timezone
            $timestamp = date_timestamp_get($dateObj) + $UTCOffset; // on ajout l'écart de timezone au timestamp pour qu'il soit correct
            $this->ordoPharmaList[$key]['time']['timestamp'] = $timestamp;
            $this->ordoPharmaList[$key]['time']['frenchDate'] = utf8_encode(ucwords(gmstrftime('%A %e %B %Y', $timestamp))); // utf8_encode() pour s'assurer que les accents passent bien
        }
    }

    /**
     *
     */
    private function prescAdder()
    {
        foreach ($this->ordoPharmaList as $ordoKey => $ordoValue) {
            foreach ($this->prescOrdoPharma as $prescKey => $prescValue) {
                if ($ordoValue['ordoPharmaID'] == $prescValue['ordoPharmaID']) {
                    array_push($this->ordoPharmaList[$ordoKey]['prescList'], $prescValue);
                }
            }
        }
    }
}
