<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

/** Gestion des ordonnances de prélèvements en laboratoire médical
 */
class OrdoLaboOrganizer
{
    private array $ordoLaboList = array();
    private array $prescOrdoLabo = array();
    private array $prescLaboElements = array();
    private array $ordoLaboSlots = array();
    private array $dateAndTime = array();

    /** Ordre de modification des ordonnances de prélèvements en laboratoire médical
     * @param array $ordoLaboList       Liste des ordonnances de prélèvements en laboratoire médical
     * @param array $prescOrdoLabo      Liste des prescriptions
     * @param array $prescLaboElements  Liste des éléments de prescriptions
     * @param array $ordoLaboSlots      Liste des slots de prélèvements
     * @param array $dateAndTime        Informations de temps utilisées pour dater les ordonnances
     * @return array                    Données modifiées
     */
    public function ordoLaboGeneralBuildOrder(
        array $ordoLaboList,
        array $prescOrdoLabo,
        array $prescLaboElements,
        array $ordoLaboSlots,
        array $dateAndTime,
    ) {
        $this->ordoLaboList = $ordoLaboList;
        $this->prescOrdoLabo = $prescOrdoLabo;
        $this->prescLaboElements = $prescLaboElements;
        $this->ordoLaboSlots = $ordoLaboSlots;
        $this->dateAndTime = $dateAndTime;

        $this->ordoLaboContentOrganizer();
        $this->prescOrganizer();
        $this->elementsAdder();
        $this->timeManagement();
        $this->prescAdder();
        $this->slotsAdder();

        //echo '<pre>';
        //    print_r($this->prescOrdoLabo);
        //echo '</pre>';

        return $this->ordoLaboList;
    }

    /** Réorganisation des données d'ordonnances de prélèvements en laboratoire médical
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

            array_push($ordoTempArray, $tempArray);
        }

        $this->ordoLaboList = $ordoTempArray;
    }

    /** Ajout d'un array à chaque prescription pour pouvoir y placer ensuite les éléments
     */
    private function prescOrganizer()
    {
        foreach ($this->prescOrdoLabo as $key => $value) {
            $this->prescOrdoLabo[$key]['elements'] = array();
        }
    }

    /** Ajouts des élements dans les prescriptions
     */
    private function elementsAdder()
    {
        foreach ($this->prescOrdoLabo as $prescKey => $prescValue) {
            foreach ($this->prescLaboElements as $elementKey => $elemValue) {
                if ($prescValue['prescLaboID'] == $elemValue['prescLaboID']) {
                    array_push($this->prescOrdoLabo[$prescKey]['elements'], $elemValue);
                }
            }
        }
    }

    /** Ajout de timestamp et de date compléte écrite en français
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

    /** Ajout des prescriptions de prélèvements
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

    /** Ajout des slots de prélèvements
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
