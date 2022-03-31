<?php

namespace HealthKerd\Controller\userAccount;

/** Contrôles fait sur les données entrées dans les champs des formulaires du user
 */
class UserFormChecker
{
    public function __destruct()
    {
    }

    /** Méthodes de contrôles des données envoyées aux formulaires du user
     * @param array $cleanedUpPost      Liste des données entrantes
     * @return array                    Statut de conformité des entrées
     */
    public function userFormChecks(array $cleanedUpPost): array
    {
        $checksResultsArray = array();

        // vérification des noms et prénoms
        $nameRegexChecker = new \HealthKerd\Services\regexStore\NameRegex();
        $checksResultsArray['lastname'] = false; // champ obligatoire
        $checksResultsArray['firstname'] = false; // champ obligatoire
        $checksResultsArray['login'] = false; // champ obligatoire

        if (strlen($cleanedUpPost['lastname']) > 0) {
            $checksResultsArray['lastname'] = $nameRegexChecker->nameRegex($cleanedUpPost['lastname']);
        }

        if (strlen($cleanedUpPost['firstname']) > 0) {
            $checksResultsArray['firstname'] = $nameRegexChecker->nameRegex($cleanedUpPost['firstname']);
        }

        if (strlen($cleanedUpPost['login']) > 0) {
            $checksResultsArray['login'] = $nameRegexChecker->nameRegex($cleanedUpPost['login']);
        }



        // vérification de la date de naissance
        $birthDateChecker = new \HealthKerd\Services\regexStore\DateRegex();
        $checksResultsArray['birthDate'] = false; // champ obligatoire

        if (strlen($cleanedUpPost['birthDate']) > 0) {
            $dateRegexResult['birthDate'] = $birthDateChecker->frenchDateRegex($cleanedUpPost['birthDate']);

            if ($dateRegexResult['birthDate']) {
                $checksResultsArray['birthDate'] = $this->dateExistenceCheck($cleanedUpPost['birthDate']);
            }
        }



        // vérification du mail
        $mailRegexChecker = new \HealthKerd\Services\regexStore\MailRegex();
        $checksResultsArray['mail'] = false; // champ obligatoire

        if (strlen($cleanedUpPost['mail']) > 0) {
            $checksResultsArray['mail'] = $mailRegexChecker->mailRegex($cleanedUpPost['mail']);
        }

        return $checksResultsArray;
    }

    /** Vérification de l'existence de la date donnée
     * * Conversion en chiffres pour supprimer d'éventuels 0 en début de nombre. Ex: 01 au lieu de 1.
     * * Permet de vérifier si la date existe ou pas avec checkdate()
     * @param string $birthDate     Date au format jj/mm/aaaa
     * @return bool                 Booléen de conformité
     */
    private function dateExistenceCheck(string $birthDate): bool
    {
        $exploDate = explode('/', $birthDate);

        $assoExplodate['day'] = intval($exploDate[0]);
        $assoExplodate['month'] = intval($exploDate[1]);
        $assoExplodate['year'] = intval($exploDate[2]);

        $dateExists = checkdate($assoExplodate['month'], $assoExplodate['day'], $assoExplodate['year']);

        return $dateExists;
    }
}
