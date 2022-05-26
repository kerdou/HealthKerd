<?php

namespace HealthKerd\Services\regexStore;

class TelRegex
{
    public function __destruct()
    {
    }

    public function telRegex(string $stringToCheck): bool
    {
        $stringToCheck = html_entity_decode($stringToCheck);

        /** ^([0]{1})                                               Commence par un 0
         *           ([1-9]{1}[. ]?)                                Suivi d'un chiffre allant de 1 à 9 suivi d'un . ou pas
         *                          ([0-9]{2}[. ]?){3}              Suivi de 3 pairs de chiffres allant de 0 à 9 suivis chacun d'un . ou pas
         *                                            ([0-9]{2})$   Se termine par 2 chiffres allant de 0 à 9
         * */
        $tel = "^([0]{1})([1-9]{1}[. ]?)([0-9]{2}[. ]?){3}([0-9]{2})$";

        $pregListForTel = "/" . $tel . "/";
        $regexResult = (preg_match($pregListForTel, $stringToCheck) ? true : false);

        return $regexResult;
    }
}
