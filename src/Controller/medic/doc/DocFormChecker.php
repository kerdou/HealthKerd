<?php

namespace HealthKerd\Controller\medic\doc;

/** Contrôles fait sur les données entrées dans les champs des formulaires des docteurs
 */
class DocFormChecker
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
                    'isRequired' => false,
                    'minLengthReq' => 2
                ),
                'checksVerdicts' => array(
                    'lengthValidity' => false,
                    'regexValidity' => false
                ),
                'overallValidityVerdict' => false
            ),
            'tel' => array(
                'value' => '',
                'checkCriterias' => array(
                    'isRequired' => false,
                    'minLengthReq' => 10
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
                    'isRequired' => false,
                    'minLengthReq' => 7
                ),
                'checksVerdicts' => array(
                    'lengthValidity' => false,
                    'regexValidity' => false
                ),
                'overallValidityVerdict' => false
            ),
            'webpage' => array(
                'value' => '',
                'checkCriterias' => array(
                    'isRequired' => false,
                    'minLengthReq' => 5
                ),
                'checksVerdicts' => array(
                    'lengthValidity' => false,
                    'regexValidity' => false
                ),
                'overallValidityVerdict' => false
            ),
            'doctolibpage' => array(
                'value' => '',
                'checkCriterias' => array(
                    'isRequired' => false,
                    'minLengthReq' => 5
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

    /** Méthodes de contrôles des données envoyées aux formulaires des docteurs
     * @param array $cleanedUpPost      Liste des données entrantes
     * @return array                    Statut de conformité des entrées
     */
    public function docFormChecks(array $cleanedUpPost): array
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
                $nameRegexChecker = new \HealthKerd\Services\regexStore\NameRegex();
                $this->formChecksArr[$field]['checksVerdicts']['regexValidity'] = $nameRegexChecker->nameRegex($this->formChecksArr[$field]['value']);
                break;

            case 'tel':
                $telRegexChecker = new \HealthKerd\Services\regexStore\TelRegex();
                $this->formChecksArr[$field]['checksVerdicts']['regexValidity'] = $telRegexChecker->telRegex($this->formChecksArr[$field]['value']);
                break;

            case 'mail':
                $mailRegexChecker = new \HealthKerd\Services\regexStore\MailRegex();
                $this->formChecksArr[$field]['checksVerdicts']['regexValidity'] = $mailRegexChecker->mailRegex($this->formChecksArr[$field]['value']);
                break;

            case 'webpage':
            case 'doctolibpage':
                $urlRegeChecker = new \HealthKerd\Services\regexStore\UrlRegex();
                $this->formChecksArr[$field]['checksVerdicts']['regexValidity'] = $urlRegeChecker->urlRegex($this->formChecksArr[$field]['value']);
                break;
        }
    }
}
