<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

class OrdoPharmaOrganizer
{
    private array $ordoPharmaList = array();
    private array $prescOrdoPharma = array();

    public function __destruct()
    {
    }

    /** Ordre des actions pour le remodelage et l'ajout des données des ordonnances pharmacologiques
     * @param array $ordoPharmaList     Liste des ordonnances pharmacologiques
     * @param array $prescOrdoPharma    Liste des prescriptions pour les ordonnances pharmacologiques
     * @return array                    Ordonnances pharmacologiques réagencées et complétées
     */
    public function ordoPharmaGeneralBuildOrder(
        array $ordoPharmaList,
        array $prescOrdoPharma,
    ): array {
        $this->ordoPharmaList = $ordoPharmaList;
        $this->prescOrdoPharma = $prescOrdoPharma;

        $this->ordoPharmaContentOrganizer();
        $this->prescAdder();

        return $this->ordoPharmaList;
    }

    /** Réorganisation des données des ordonnances
     */
    private function ordoPharmaContentOrganizer()
    {
        $ordoTempArray = array();

        foreach ($this->ordoPharmaList as $value) {
            // service de gestion du temps
            $dateAndTimeManagementBuilder = new \HealthKerd\Services\common\DateAndTimeManagement();
            $dateAndTimeProcessedData = $dateAndTimeManagementBuilder->dateAndTimeConverter(
                $value['ordoDate'],
                $_ENV['DATEANDTIME']['timezoneObj']
            );

            $tempArray['ordoPharmaID'] = $value['ordoPharmaID'];
            $tempArray['ordoType'] = 'pharma';
            $tempArray['diagID'] = $value['diagID'];

            $tempArray['time']['date'] = $value['ordoDate'];
            $tempArray['time']['timestamp'] = $dateAndTimeProcessedData['timestamp'];
            $tempArray['time']['frenchDate'] = $dateAndTimeProcessedData['frenchDate'];

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
}
