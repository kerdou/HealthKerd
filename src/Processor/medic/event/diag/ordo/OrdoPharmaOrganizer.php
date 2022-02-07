<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

class OrdoPharmaOrganizer
{
    private array $ordoPharmaList = array();
    private array $prescOrdoPharma = array();
    private array $dateAndTime = array();

    /** Ordre des actions pour le remodelage et l'ajout des données des ordonnances pharmacologiques
     * @param array $ordoPharmaList     Liste des ordonnances pharmacologiques
     * @param array $prescOrdoPharma    Liste des prescriptions pour les ordonnances pharmacologiques
     * @param array $dateAndTime        Informations de temps utilisées pour dater les ordonnances
     * @return array                    Ordonnances pharmacologiques réagencées et complétées
     */
    public function ordoPharmaGeneralBuildOrder(
        array $ordoPharmaList,
        array $prescOrdoPharma,
        array $dateAndTime,
    ) {
        $this->ordoPharmaList = $ordoPharmaList;
        $this->prescOrdoPharma = $prescOrdoPharma;
        $this->dateAndTime = $dateAndTime;

        $this->ordoPharmaContentOrganizer();
        $this->prescAdder();
        $this->timeManagement();

        //echo '<pre>';
        //    print_r($this->ordoPharmaList);
        //echo '</pre>';

        return $this->ordoPharmaList;
    }

    /** Réorganisation des données des ordonnances
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

            array_push($ordoTempArray, $tempArray);
        }

        $this->ordoPharmaList = $ordoTempArray;
    }

    /** Ajout des prescriptions dans les ordonnances voulues
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

    /** Ajout de timestamp et de date compléte écrite en français
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
}
