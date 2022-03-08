<?php

namespace HealthKerd\Services\regexStore;

class MailRegex
{
    public function __destruct()
    {
    }

    public function mailRegex(string $stringToCheck): bool
    {
        $stringToCheck = html_entity_decode($stringToCheck);

        /** ^               Doit être placé au début de l'adresse mail
         *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste  */
        $mailBeginning = "^[a-z0-9._\-]+";

        /** @               Doit être placé après l'arobase
         *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste */
        $mailMiddle = "@[a-z0-9._\-]+";

        /** \.         $   Doit être placé entre un . et la fin de l'adresse.
         *     [a-z]{2,6}   Doit contenir entre 2 et 6 lettres présents dans la liste */
        $mailEnding = "\.[a-z]{2,6}$";

        $pregListForMail = "/" . $mailBeginning . $mailMiddle . $mailEnding . "/i";
        $regexResult = (preg_match($pregListForMail, $stringToCheck) ? true : false);

        return $regexResult;
    }
}
