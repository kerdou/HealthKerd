<?php

namespace HealthKerd\Controller\userAccount;

/** Contrôles fait sur les données entrées dans les champs des formulaires de changement de mot de passe
 */
class PwdFormChecker
{
    private array $minimalQtyCriteriasArr = array();
    private array $formChecksArr = array();

    public function __construct()
    {
        $this->minimalQtyCriteriasArr = array(
            'length' => 8,
            'lower' => 1,
            'upper' => 1,
            'nbr' => 1,
            'spe' => 1
        );

        $this->formChecksArr = array(
            'pwd' => array(
                'value' => '',
                'regexResults' => array(
                    'length' => 0,
                    'lower' => 0,
                    'upper' => 0,
                    'nbr' => 0,
                    'spe' => 0
                ),
                'checksVerdicts' => array(
                    'length' => false,
                    'lower' => false,
                    'upper' => false,
                    'nbr' => false,
                    'spe' => false
                ),
                'overallValidityVerdict' => false
            ),
            'confPwd' => array(
                'value' => '',
                'regexResults' => array(
                    'length' => 0,
                    'lower' => 0,
                    'upper' => 0,
                    'nbr' => 0,
                    'spe' => 0
                ),
                'checksVerdicts' => array(
                    'length' => false,
                    'lower' => false,
                    'upper' => false,
                    'nbr' => false,
                    'spe' => false
                ),
                'overallValidityVerdict' => false
            )
        );
    }


    public function __destruct()
    {
    }


    /** Méthodes de contrôles des données envoyées aux formulaires des docteurs
     * @param array $cleanedUpPost      Liste des données entrantes
     * @return array                    Statut de conformité des entrées
     */
    public function pwdFormChecks(array $cleanedUpPost): array
    {
        $pwdRegexChecker = new \HealthKerd\Services\regexStore\PwdRegex();

        foreach ($cleanedUpPost as $fieldKey => $fieldValue) {
            $this->formChecksArr[$fieldKey]['value'] = $fieldValue;
            $regexFeedback = $pwdRegexChecker->pwdRegex($this->formChecksArr[$fieldKey]['value']);

            foreach ($regexFeedback as $regexKey => $regexValue) {
                $this->formChecksArr[$fieldKey]['regexResults'][$regexKey] = $regexValue;
                $this->formChecksArr[$fieldKey]['checksVerdicts'][$regexKey] = ($regexValue >= $this->minimalQtyCriteriasArr[$regexKey]) ? true : false;
            }

            $this->formChecksArr[$fieldKey]['overallValidityVerdict'] = (in_array(false, $this->formChecksArr[$fieldKey]['checksVerdicts'])) ? false : true;
        }

        return $this->formChecksArr;
    }
}
