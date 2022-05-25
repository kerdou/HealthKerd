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

        /** ^                                 Doit être placé au début du numéro de tel
         *   ^([0-9]{2}[. ]?){4}              4 séries de 2 chiffres séparées par un point ou sans séparation
         *                      ([0-9]{2})$   Suivies de 2 chiffres
         * */
        $tel = "^([0-9]{2}[. ]?){4}([0-9]{2})$";

        $pregListForTel = "/" . $tel . "/";
        $regexResult = (preg_match($pregListForTel, $stringToCheck) ? true : false);

        return $regexResult;
    }
}
