<?php

namespace HealthKerd\Controller\userAccount;

/** Contrôles fait sur les données entrées dans les champs des formulaires de changement de mot de passe
 */
class PwdFormChecker
{
    public function __destruct()
    {
    }

    /** Méthodes de contrôles des données envoyées aux formulaires des docteurs
     * @param array $cleanedUpPost      Liste des données entrantes
     * @return array                    Statut de conformité des entrées
     */
    public function pwdFormChecks(array $cleanedUpPost): array
    {
        $checksResultsArray = array();
        $pwdRegexChecker = new \HealthKerd\Services\regexStore\PwdRegex();

        $checksResultsArray['overall']['identical'] = false; // pwd identiques obligatoires
        $checksResultsArray['overall']['areValid'] = false;
        $checksResultsArray['pwd']['status']['isValid'] = false; // champ obligatoire
        $checksResultsArray['confPwd']['status']['isValid'] = false; // champ obligatoire


        // vérifie que les 2 mdp soient identiques
        if ($cleanedUpPost['pwd'] ==  $cleanedUpPost['confPwd']) {
            $checksResultsArray['overall']['identical'] = true;
        }

        // vérification de la conformité du champ 'pwd'
        $checksResultsArray['pwd']['summary'] = $pwdRegexChecker->pwdRegex($cleanedUpPost['pwd']);
        $checksResultsArray['pwd']['status'] = $this->rulesChecker($checksResultsArray['pwd']['summary']);


        // vérification de la conformité du champ 'confPwd'
        $checksResultsArray['confPwd']['summary'] = $pwdRegexChecker->pwdRegex($cleanedUpPost['confPwd']);
        $checksResultsArray['confPwd']['status'] = $this->rulesChecker($checksResultsArray['confPwd']['summary']);


        if (($checksResultsArray['pwd']['status']['isValid'] == true) && ($checksResultsArray['confPwd']['status']['isValid'] == true)) {
            $checksResultsArray['overall']['areValid'] = true;
        }

        return $checksResultsArray;
    }

    /**
     *
     */
    private function rulesChecker(array $pwdSummary): array
    {
        $result['length'] = ($pwdSummary['length'] >= 8) ? true : false;
        $result['lower'] = ($pwdSummary['lower'] >= 1) ? true : false;
        $result['upper'] = ($pwdSummary['upper'] >= 1) ? true : false;
        $result['nbr'] = ($pwdSummary['nbr'] >= 1) ? true : false;
        $result['spe'] = ($pwdSummary['spe'] >= 1) ? true : false;

        $result['isValid'] = (in_array(false, $result)) ? false : true;

        return $result;
    }
}
