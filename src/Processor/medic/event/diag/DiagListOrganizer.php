<?php

namespace HealthKerd\Processor\medic\event\diag;

/** Remodelage des diagnostics et de leurs ordonnances
 */
class DiagListOrganizer
{
    private array $diagList = array();
    private array $diagSymptoms = array();
    private array $diagCheckpoints = array();
    private array $diagConclusions = array();
    private array $ordoGlobalArray = array();

    public function __destruct()
    {
    }

    /** Suite des actions servant à rassembler et recombiner les données des diagnostics
     * Comprend également la gestion des ordonnances suivantes:
     * * Pharmaceutique
     * * Prélévement en laboratoire médical
     * * Optique
     * * Vaccinal
     * @param array $diagList           Liste des diagnostics
     * @param array $diagSymptoms       Liste des symptômes
     * @param array $diagCheckpoints    Liste des points de contrôle
     * @param array $diagConclusions    Liste des conclusions
     * @param array $ordoGlobalArray    Liste regroupant toutes les ordonnances déjà classées par timestamp en ordre croissant
     * @return array                    Diagnostics complets avec leurs ordonnances respectives
     */
    public function diagListArrayGeneralBuildOrder(
        array $diagList,
        array $diagSymptoms,
        array $diagCheckpoints,
        array $diagConclusions,
        array $ordoGlobalArray
    ) {
        $this->diagList = $diagList;
        $this->diagSymptoms = $diagSymptoms;
        $this->diagCheckpoints = $diagCheckpoints;
        $this->diagConclusions = $diagConclusions;
        $this->ordoGlobalArray = $ordoGlobalArray;

        $this->diagListOrganizer();
        $this->checkpointsAddition();
        $this->symptomsAddition();
        $this->conclusionsAddition();
        $this->ordoAddition();

        return $this->diagList;
    }

    /** Réorganisation des données des diagnostics afin de faciliter la suite
     */
    private function diagListOrganizer()
    {
        $diagListTempArray = array();

        foreach ($this->diagList as $value) {
            $tempArray = array();

            $tempArray['diagID'] = $value['diagID'];
            $tempArray['contentType'] = 'diag';
            $tempArray['medicEventID'] = $value['medicEventID'];
            $tempArray['comment'] = $value['comment'];

            $tempArray['symptoms'] = array();
            $tempArray['checkpoints'] = array();
            $tempArray['conclusions'] = array();

            $tempArray['ordo'] = array();

            array_push($diagListTempArray, $tempArray);
        }

        $this->diagList = $diagListTempArray;
    }

    /** Ajout des symptômes des diagnostics
     */
    private function symptomsAddition()
    {
        foreach ($this->diagList as $diagKey => $diagValue) {
            foreach ($this->diagSymptoms as $symptKey => $symptValue) {
                if ($diagValue['diagID'] == $symptValue['diagID']) {
                    array_push($this->diagList[$diagKey]['symptoms'], $symptValue);
                }
            }
        }
    }

    /** Ajout des points de contrôle des diagnostics
     */
    private function checkpointsAddition()
    {
        foreach ($this->diagList as $diagKey => $diagValue) {
            foreach ($this->diagCheckpoints as $checkKey => $checkValue) {
                if ($diagValue['diagID'] == $checkValue['diagID']) {
                    array_push($this->diagList[$diagKey]['checkpoints'], $checkValue);
                }
            }
        }
    }

    /** Ajout des conclusions des diagnostics
     */
    private function conclusionsAddition()
    {
        foreach ($this->diagList as $diagKey => $diagValue) {
            foreach ($this->diagConclusions as $conclKey => $conclValue) {
                if ($diagValue['diagID'] == $conclValue['diagID']) {
                    array_push($this->diagList[$diagKey]['conclusions'], $conclValue);
                }
            }
        }
    }

    /** Ajout des ordonnances
     */
    private function ordoAddition()
    {
        foreach ($this->diagList as $diagKey => $diagValue) {
            foreach ($this->ordoGlobalArray as $ordoKey => $ordoValue) {
                if ($diagValue['diagID'] == $ordoValue['diagID']) {
                    array_push($this->diagList[$diagKey]['ordo'], $ordoValue);
                }
            }
        }
    }
}
