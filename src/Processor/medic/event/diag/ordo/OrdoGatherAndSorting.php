<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

/** Toutes les ordonnances sont réunies dans un array, classées par timestamp puis renvoyées
 */
class OrdoGatherAndSorting
{
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
    ): array {
        $ordoGlobalArray = [
            ...$ordoLabo,
            ...$ordoPharma,
            ...$ordoVax,
            ...$ordoSight
        ];

        $timestampSorter = new \HealthKerd\Services\common\TimestampSorting();
        $ordoGlobalArray = $timestampSorter->incrTimestampSortLauncher($ordoGlobalArray);

        return $ordoGlobalArray;
    }
}
