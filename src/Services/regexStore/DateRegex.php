<?php

namespace HealthKerd\Services\regexStore;

class DateRegex
{
    public function __destruct()
    {
    }

    /** Vérification des dates au format français
     * * Format: jj/mm/aaaa
     * * Les jours et les mois peuvent être à 1 ou 2 chiffres
     * * L'année est forcément à 4 chiffres
     * @param string $stringToCheck     Date à vérifier
     * @return bool                     Résultat du test du regex
     */
    public function frenchDateRegex(string $stringToCheck): bool
    {
        $pregListForFrenchDate = "/^\d{1,2}\/\d{1,2}\/\d{4}$/";
        $regexResult = (preg_match($pregListForFrenchDate, $stringToCheck) ? true : false);

        return $regexResult;
    }
}
