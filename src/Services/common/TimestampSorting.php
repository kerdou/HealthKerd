<?php

namespace HealthKerd\Services\common;

/** Classe de tri par timestamp
 */
class TimestampSorting
{
    public function __destruct()
    {
    }

    /** Lancement du tri en ordre croissant des timestamps
     * * Lance incrTimestampSorting()
     * @param array $items  Liste des éléments à trier
     * @return array        Eléments triés
    */
    public function incrTimestampSortLauncher(array $items): array
    {
        uasort($items, array($this, "incrTimestampSorting"));
        return $items;
    }

    /** Tri en ordre croissant des timestamps
     * @param array $firstItem      1er élément à comparer
     * @param array $secondItem     2éme élément à comparer
     * @return int                  Résultat de la comparaison pour uasort()
    */
    private function incrTimestampSorting(array $firstItem, array $secondItem): int
    {
        if ($firstItem['time']['timestamp'] == $secondItem['time']['timestamp']) {
            return 0;
        }
        return ($firstItem['time']['timestamp'] < $secondItem['time']['timestamp']) ? -1 : 1;
    }

    /** Lancement du tri en ordre décroissant des timestamps
     * * Lance decrTimestampSorting()
     * @param array $items  Liste des éléments à trier
     * @return array        Eléments triés
    */
    public function decrTimestampSortLauncher(array $items): array
    {
        uasort($items, array($this, "decrTimestampSorting"));
        return $items;
    }

    /** Tri en ordre décroissant des timestamps
     * @param array $firstItem      1er élément à comparer
     * @param array $secondItem     2éme élément à comparer
     * @return int                  Résultat de la comparaison pour uasort()
    */
    private function decrTimestampSorting(array $firstItem, array $secondItem): int
    {
        if ($firstItem['time']['timestamp'] == $secondItem['time']['timestamp']) {
            return 0;
        }
        return ($firstItem['time']['timestamp'] > $secondItem['time']['timestamp']) ? -1 : 1;
    }
}
