<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

/** Toutes les ordonnances sont réunies dans un array, classées par timestamp puis renvoyées
 */
class OrdoGatherAndSorting
{
    private array $ordoGlobalArray = array();

    /**
     * @param array $ordoLabo       Liste des ordonnances de prélèvement médical déjà complétées
     * @param array $ordoPharma     Liste des ordonnances pharmacologiques déjà complétées
     * @param array $ordoVax        Liste des ordonnances vaccinales déjà complétées
     * @param array $ordoSight      Liste des ordonnances optiques déjà complétées
     * @return array                Ensembles des ordonnances triées par timestamp
     */
    public function ordoArrayGeneralBuildOrder(
        array $ordoLabo,
        array $ordoPharma,
        array $ordoVax,
        array $ordoSight
    ) {
        $this->ordoGlobalArray = [
            ...$ordoLabo,
            ...$ordoPharma,
            ...$ordoVax,
            ...$ordoSight
        ];

        uasort($this->ordoGlobalArray, array($this, "timestampSorting")); // tri des ordonnances par ordre croissant des timestamps

        //echo '<pre>';
        //    print_r($this->ordoGlobalArray);
        //echo '</pre>';

        return $this->ordoGlobalArray;
    }

    /** Tri des ordonnances par timestamp en ordre croissant
    */
    private function timestampSorting(array $firstValue, array $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] < $secondValue['time']['timestamp']) ? -1 : 1;
    }
}
