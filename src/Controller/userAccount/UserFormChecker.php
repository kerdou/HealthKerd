<?php

namespace HealthKerd\Controller\userAccount;

use SebastianBergmann\Environment\Console;

/** Contrôles fait sur les données entrées dans les champs des formulaires du user
 */
class UserFormChecker
{
    private array $formChecksArr = array();

    public function __construct()
    {
        $this->formChecksArr = array(
            'lastname' => array(
                'value' => '',
                'checkCriterias' => array(
                    'isRequired' => true,
                    'minLengthReq' => 2
                ),
                'checksVerdicts' => array(
                    'lengthValidity' => false,
                    'regexValidity' => false
                ),
                'overallValidityVerdict' => false
            ),
            'firstname' => array(
                'value' => '',
                'checkCriterias' => array(
                    'isRequired' => true,
                    'minLengthReq' => 2
                ),
                'checksVerdicts' => array(
                    'lengthValidity' => false,
                    'regexValidity' => false
                ),
                'overallValidityVerdict' => false
            ),
            'birthDate' => array(
                'value' => '',
                'checkCriterias' => array(
                    'isRequired' => true,
                    'minLengthReq' => 10
                ),
                'checksVerdicts' => array(
                    'lengthValidity' => false,
                    'regexValidity' => false
                ),
                'overallValidityVerdict' => false
            ),
            'login' => array(
                'value' => '',
                'checkCriterias' => array(
                    'isRequired' => true,
                    'minLengthReq' => 5
                ),
                'checksVerdicts' => array(
                    'lengthValidity' => false,
                    'regexValidity' => false
                ),
                'overallValidityVerdict' => false
            ),
            'mail' => array(
                'value' => '',
                'checkCriterias' => array(
                    'isRequired' => true,
                    'minLengthReq' => 7
                ),
                'checksVerdicts' => array(
                    'lengthValidity' => false,
                    'regexValidity' => false
                ),
                'overallValidityVerdict' => false
            )
        );
    }


    public function __destruct()
    {
    }

    /** Méthodes de contrôles des données envoyées aux formulaires du user
     * @param array $cleanedUpPost      Liste des données entrantes
     * @return array                    Statut de conformité des entrées
     */
    public function userFormChecks(array $cleanedUpPost): array
    {
        foreach ($cleanedUpPost as $key => $value) {
            $this->formChecksArr[$key]['value'] = $value;
        }

        foreach ($this->formChecksArr as $field => $params) {
            $fieldLength = strlen($this->formChecksArr[$field]['value']);

            if ($fieldLength == 0) {
                if ($this->formChecksArr[$field]['checkCriterias']['isRequired']) {
                    $this->formChecksArr[$field]['checksVerdicts']['lengthValidity'] = false;
                    $this->formChecksArr[$field]['overallValidityVerdict'] = false;
                } else {
                    $this->formChecksArr[$field]['checksVerdicts']['lengthValidity'] = true;
                    $this->formChecksArr[$field]['overallValidityVerdict'] = true;
                }
            } else {
                if ($fieldLength < $this->formChecksArr[$field]['checkCriterias']['minLengthReq']) {
                    $this->formChecksArr[$field]['checksVerdicts']['lengthValidity'] = false;
                    $this->formChecksArr[$field]['overallValidityVerdict'] = false;
                } else {
                    $this->formChecksArr[$field]['checksVerdicts']['lengthValidity'] = true;
                    $this->regexFieldCheck($field);

                    if ($this->formChecksArr[$field]['checksVerdicts']['regexValidity']) {
                        $this->formChecksArr[$field]['overallValidityVerdict'] = true;
                    } else {
                        $this->formChecksArr[$field]['overallValidityVerdict'] = false;
                    }
                }
            }
        }

        return $this->formChecksArr;
    }


    /** Lancement des regex selon l'input
     * @param string $field Champ à checker
     */
    private function regexFieldCheck(string $field)
    {
        switch ($field) {
            case 'lastname':
            case 'firstname':
            case 'login':
                $nameRegexChecker = new \HealthKerd\Services\regexStore\NameRegex();
                $this->formChecksArr[$field]['checksVerdicts']['regexValidity'] = $nameRegexChecker->nameRegex($this->formChecksArr[$field]['value']);
                break;

            case 'birthDate':
                $birthDateChecker = new \HealthKerd\Services\regexStore\DateRegex();
                $this->formChecksArr[$field]['checksVerdicts']['regexValidity'] = $birthDateChecker->frenchDateRegex($this->formChecksArr[$field]['value']);

                if ($this->formChecksArr[$field]['checksVerdicts']['regexValidity']) {
                    $this->formChecksArr[$field]['checksVerdicts']['regexValidity'] = $this->dateExistenceCheck($this->formChecksArr[$field]['value']);
                }
                break;

            case 'mail':
                $mailRegexChecker = new \HealthKerd\Services\regexStore\MailRegex();
                $this->formChecksArr[$field]['checksVerdicts']['regexValidity'] = $mailRegexChecker->mailRegex($this->formChecksArr[$field]['value']);
                break;
        }
    }



    /** Vérification de l'existence de la date donnée
     * * Conversion en chiffres pour supprimer d'éventuels 0 en début de nombre. Ex: 01 au lieu de 1.
     * * Permet de vérifier si la date existe, si elle est dans le passé et si la personne a plus de 18 ans.
     * @param string $birthDate     Date au format jj/mm/aaaa
     * @return bool                 Booléen de conformité
     */
    private function dateExistenceCheck(string $birthDate): bool
    {
        $exploDate = explode('/', $birthDate);

        $assoExplodate['day'] = intval($exploDate[0]);
        $assoExplodate['month'] = intval($exploDate[1]);
        $assoExplodate['year'] = intval($exploDate[2]);

        $birthDateString = (string) $assoExplodate['year'] . '-' . $assoExplodate['month'] . '-' . $assoExplodate['day'];
        $birthDateObj = date_create($birthDateString, $_ENV['DATEANDTIME']['timezoneObj']);

        $dateCheckSummary = array(
            'dateExists' => checkdate($assoExplodate['month'], $assoExplodate['day'], $assoExplodate['year']),
            'isInThePast' => false,
            'oldEnough' => false
        );

        if ($dateCheckSummary['dateExists']) {
            $dateCheckSummary['isInThePast'] = ($birthDateObj < $_ENV['DATEANDTIME']['nowDate']['nowTimeObj']) ? true : false;

            $timeDiff = $birthDateObj->diff($_ENV['DATEANDTIME']['nowDate']['nowTimeObj']);
            $yearsDiffString = $timeDiff->format('%y');
            $yearsNumber = (int)$yearsDiffString;
            $dateCheckSummary['oldEnough'] = ($yearsNumber > 18) ? true : false;
        }

        $finalVerdict = false;

        if (!in_array(false, $dateCheckSummary)) {
            $finalVerdict = true;
        }

        return $finalVerdict;
    }
}
