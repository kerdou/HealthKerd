<?php

namespace HealthKerd\Controller\medic\doc;

/** Contrôles fait sur les données entrées dans les champs des formulaires des docteurs
 */
class DocFormChecker
{
    public function __destruct()
    {
    }

    /** Méthodes de contrôles des données envoyées aux formulaires des docteurs
     * @param array $cleanedUpPost      Liste des données entrantes
     * @return array                    Statut de conformité des entrées
     */
    public function docFormChecks(array $cleanedUpPost): array
    {
        $checksResultsArray = array();

        // vérification des noms et prénoms
        $nameRegexChecker = new \HealthKerd\Services\regexStore\NameRegex();
        $checksResultsArray['lastname'] = false; // champ obligatoire
        $checksResultsArray['firstname'] = true; // champ facultatif

        if (strlen($cleanedUpPost['lastname']) > 0) {
            $checksResultsArray['lastname'] = $nameRegexChecker->nameRegex($cleanedUpPost['lastname']);
        }

        if (strlen($cleanedUpPost['firstname']) > 0) {
            $checksResultsArray['firstname'] = $nameRegexChecker->nameRegex($cleanedUpPost['firstname']);
        }



        // vérification du numéro de tel
        $telRegexChecker = new \HealthKerd\Services\regexStore\TelRegex();
        $checksResultsArray['tel'] = true; // champ facultatif

        if (strlen($cleanedUpPost['tel']) > 0) {
            $checksResultsArray['tel'] = $telRegexChecker->telRegex($cleanedUpPost['tel']);
        }



        // vérification du mail
        $mailRegexChecker = new \HealthKerd\Services\regexStore\MailRegex();
        $checksResultsArray['mail'] = true; // champ facultatif

        if (strlen($cleanedUpPost['mail']) > 0) {
            $checksResultsArray['mail'] = $mailRegexChecker->mailRegex($cleanedUpPost['mail']);
        }



        // vérification des URL de site perso et de page doctolib
        $urlRegeChecker = new \HealthKerd\Services\regexStore\UrlRegex();
        $checksResultsArray['webpage'] = true; // champ facultatif
        $checksResultsArray['doctolibpage'] = true; // champ facultatif

        if (strlen($cleanedUpPost['webpage']) > 0) {
            $checksResultsArray['webpage'] = $urlRegeChecker->urlRegex($cleanedUpPost['webpage']);
        }

        if (strlen($cleanedUpPost['doctolibpage']) > 0) {
            $checksResultsArray['doctolibpage'] = $urlRegeChecker->urlRegex($cleanedUpPost['doctolibpage']);
        }

        return $checksResultsArray;
    }
}
