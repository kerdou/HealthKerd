<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

/** Gestion des ordonnances vaccinales
 */
class OrdoVaxOrganizer
{
    private array $ordoVaxList = array();
    private array $prescOrdoVax = array();
    private array $ordoVaxSlots = array();

    /** Ordre de modification des ordonnances vaccinales
     * @param array $ordoVaxList    Liste des ordonnances vaccinales
     * @param array $prescOrdoVax   Liste des prescriptions
     * @param array $ordoVaxSlots   Liste des slots de vaccination
     * @return array                Données modifiées
     */
    public function ordoVaxGeneralBuildOrder(
        array $ordoVaxList,
        array $prescOrdoVax,
        array $ordoVaxSlots
    ): array {
        $this->ordoVaxList = $ordoVaxList;
        $this->prescOrdoVax = $prescOrdoVax;
        $this->ordoVaxSlots = $ordoVaxSlots;

        $this->ordoVaxContentOrganizer();
        $this->prescAdder();
        $this->slotsAdder();

        return $this->ordoVaxList;
    }

    /** Réorganisation des données d'ordonnances de prélèvements en laboratoire médical
     */
    private function ordoVaxContentOrganizer()
    {
        $ordoTempArray = array();

        foreach ($this->ordoVaxList as $value) {
            // service de gestion du temps
            $dateAndTimeManagementBuilder = new \HealthKerd\Services\common\DateAndTimeManagement();
            $dateAndTimeProcessedData = $dateAndTimeManagementBuilder->dateAndTimeConverter(
                $value['date'],
                $_ENV['DATEANDTIME']['timezoneObj']
            );

            $tempArray['ordoVaxID'] = $value['ordoVaxID'];
            $tempArray['ordoType'] = 'vax';
            $tempArray['diagID'] = $value['diagID'];

            $tempArray['time']['date'] = $value['date'];
            $tempArray['time']['timestamp'] = $dateAndTimeProcessedData['timestamp'];
            $tempArray['time']['frenchDate'] = $dateAndTimeProcessedData['frenchDate'];

            $tempArray['comment'] = $value['comment'];

            $tempArray['prescList'] = array();
            $tempArray['slotsList'] = array();

            array_push($ordoTempArray, $tempArray);
        }

        $this->ordoVaxList = $ordoTempArray;
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
