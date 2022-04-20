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

        /** ^                       Doit être placé au début du numéro de tel
         *   (([. ]?[0-9]{2}){5}    Des séries de 2 chiffres séparées par des points ou sans séparation*/
        $tel = '^([. ]?[0-9]{2}){5}';

        $pregListForTel = "/" . $tel . "/";
        $regexResult = (preg_match($pregListForTel, $stringToCheck) ? true : false);

        return $regexResult;
    }
}
