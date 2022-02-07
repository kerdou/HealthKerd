<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

/** Gestion des ordonnances vaccinales
 */
class OrdoVaxOrganizer
{
    private array $ordoVaxList = array();
    private array $prescOrdoVax = array();
    private array $ordoVaxSlots = array();
    private array $dateAndTime = array();

    /** Ordre de modification des ordonnances vaccinales
     * @param array $ordoVaxList    Liste des ordonnances vaccinales
     * @param array $prescOrdoVax   Liste des prescriptions
     * @param array $ordoVaxSlots   Liste des slots de vaccination
     * @param array $dateAndTime    Informations de temps utilisées pour dater les ordonnances
     * @return array                Données modifiées
     */
    public function ordoVaxGeneralBuildOrder(
        array $ordoVaxList,
        array $prescOrdoVax,
        array $ordoVaxSlots,
        array $dateAndTime
    ) {
        $this->ordoVaxList = $ordoVaxList;
        $this->prescOrdoVax = $prescOrdoVax;
        $this->ordoVaxSlots = $ordoVaxSlots;
        $this->dateAndTime = $dateAndTime;

        $this->ordoVaxContentOrganizer();
        $this->timeManagement();
        $this->prescAdder();
        $this->slotsAdder();

        //echo '<pre>';
        //    print_r($this->ordoVaxList);
        //echo '</pre>';

        return $this->ordoVaxList;
    }

    /** Réorganisation des données d'ordonnances de prélèvements en laboratoire médical
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

            array_push($ordoTempArray, $tempArray);
        }

        $this->ordoVaxList = $ordoTempArray;
    }

    /** Ajout de timestamp et de date compléte écrite en français
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

    /** Ajout des prescriptions vaccinales
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

    /** Ajout des slots de vaccination
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
