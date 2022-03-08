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

    /** Ordre de modification des ordonnances de prélèvements en laboratoire médical
     * @param array $ordoLaboList       Liste des ordonnances de prélèvements en laboratoire médical
     * @param array $prescOrdoLabo      Liste des prescriptions
     * @param array $prescLaboElements  Liste des éléments de prescriptions
     * @param array $ordoLaboSlots      Liste des slots de prélèvements
     * @return array                    Données modifiées
     */
    public function ordoLaboGeneralBuildOrder(
        array $ordoLaboList,
        array $prescOrdoLabo,
        array $prescLaboElements,
        array $ordoLaboSlots
    ): array {
        $this->ordoLaboList = $ordoLaboList;
        $this->prescOrdoLabo = $prescOrdoLabo;
        $this->prescLaboElements = $prescLaboElements;
        $this->ordoLaboSlots = $ordoLaboSlots;

        $this->ordoLaboContentOrganizer();
        $this->prescOrganizer();
        $this->elementsAdder();
        $this->prescAdder();
        $this->slotsAdder();

        return $this->ordoLaboList;
    }

    /** Réorganisation des données d'ordonnances de prélèvements en laboratoire médical
     */
    private function ordoLaboContentOrganizer()
    {
        $ordoTempArray = array();

        foreach ($this->ordoLaboList as $value) {
            // service de gestion du temps
            $dateAndTimeManagementBuilder = new \HealthKerd\Services\common\DateAndTimeManagement();
            $dateAndTimeProcessedData = $dateAndTimeManagementBuilder->dateAndTimeConverter(
                $value['date'],
                $_ENV['DATEANDTIME']['timezoneObj']
            );

            $tempArray['ordoLaboID'] = $value['ordoLaboID'];
            $tempArray['ordoType'] = 'laboSampling';
            $tempArray['diagID'] = $value['diagID'];

            $tempArray['time']['date'] = $value['date'];
            $tempArray['time']['timestamp'] = $dateAndTimeProcessedData['timestamp'];
            $tempArray['time']['frenchDate'] = $dateAndTimeProcessedData['frenchDate'];

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
