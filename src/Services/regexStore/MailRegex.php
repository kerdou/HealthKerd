<?php

namespace HealthKerd\Services\regexStore;

class MailRegex
{
    public function __destruct()
    {
    }

    public function mailRegex(string $stringToCheck): bool
    {
        //    ^[a-z0-9]?(?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*[a-z0-9]+@[a-z0-9]+(?:[\.\-][a-z0-9]+)*\.[a-z]{2,8}$    i
        $stringToCheck = html_entity_decode($stringToCheck);

        /** Avant l'arobase
         * ^[a-z0-9]?(?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*[a-z0-9]+
         */
        /** Le 1er caractére de l'adresse doit être [a-z0-9] ou ne pas exister     */
        $addrStart = '^[a-z0-9]?';

        /** Partie optionnelle:
         *  commençant par au moins 1 [a-z0-9] possiblement suivi d'un [.!#$%&'*+-/=?^_\`{|}~]
         * (?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*     */
        $addrOptionnalMidPortion = "(?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*";

        /** L'adresse avant l'arobase doit impérativement se terminer par un caractére [a-z0-9]    */
        $addrEnd = '[a-z0-9]+';

        $completeAddr = $addrStart . $addrOptionnalMidPortion . $addrEnd;


        /** Après l'arobase
         * @[a-z0-9]+(?:[\.\-][a-z0-9]+)*\.[a-z]{2,8}$
         */

        /** Le 1er char derriere @ doit être [a-z0-9]    */
        $domAfterArobase = '@[a-z0-9]+';

        /** Partie optionnelle:
         * Doit commencer par un . ou un - suivi d'au moins un [a-z0-9]
         * Peut être présent zéro ou plusieurs fois
         * (?:[\.\-][a-z0-9]+)*         */
        $domOptionnalMidPortion = '(?:[\.\-][a-z0-9]+)*';

        /** Le domaine doit se terminer avec un . suivi de 2 à 8 [a-z]
         * \.[a-z]{2,8}$  */
        $domEnding = '\.[a-z]{2,8}$';

        $completeDom = $domAfterArobase . $domOptionnalMidPortion . $domEnding;

        $mailConcat = $completeAddr . $completeDom;

        $pregListForMail = "/" . $mailConcat . "/i";
        $regexResult = (preg_match($pregListForMail, $stringToCheck) ? true : false);

        return $regexResult;
    }
}
