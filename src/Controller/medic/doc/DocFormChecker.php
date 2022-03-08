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

        if (strlen($cleanedUpPost['lastname']) > 0) {
            $checksResultsArray['lastname'] = $nameRegexChecker->nameRegex($cleanedUpPost['lastname']);
        } else {
            $checksResultsArray['lastname'] = false;
        }

        if (strlen($cleanedUpPost['firstname']) > 0) {
            $checksArray['firstname'] = $nameRegexChecker->nameRegex($cleanedUpPost['firstname']);
        } else {
            $checksArray['firstname'] = true;
        }



        // vérification du numéro de tel
        $telRegexChecker = new \HealthKerd\Services\regexStore\TelRegex();

        if (strlen($cleanedUpPost['tel']) > 0) {
            $checksResultsArray['tel'] = $telRegexChecker->telRegex($cleanedUpPost['tel']);
        } else {
            $checksResultsArray['tel'] = true;
        }



        // vérification du mail
        $mailRegexChecker = new \HealthKerd\Services\regexStore\MailRegex();

        if (strlen($cleanedUpPost['mail'])) {
            $checksResultsArray['mail'] = $mailRegexChecker->mailRegex($cleanedUpPost['mail']);
        } else {
            $checksResultsArray['mail'] = true;
        }



        // vérification des URL de site perso et de page doctolib
        $urlRegeChecker = new \HealthKerd\Services\regexStore\UrlRegex();

        if (strlen($cleanedUpPost['webpage']) > 0) {
            $checksResultsArray['webpage'] = $urlRegeChecker->urlRegex($cleanedUpPost['webpage']);
        } else {
            $checksResultsArray['webpage'] = true;
        }

        if (strlen($cleanedUpPost['doctolibpage']) > 0) {
            $checksResultsArray['doctolibpage'] = $urlRegeChecker->urlRegex($cleanedUpPost['doctolibpage']);
        } else {
            $checksResultsArray['doctolibpage'] = true;
        }

        return $checksResultsArray;
    }
}
