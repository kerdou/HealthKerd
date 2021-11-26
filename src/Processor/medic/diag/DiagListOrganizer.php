<?php

namespace HealthKerd\Processor\medic\diag;

class DiagListOrganizer
{
    private array|null $diagList = array();
    private array|null $diagCheckpoints = array();
    private array|null $diagThemes = array();
    private array|null $diagSymptoms = array();
    private array|null $diagConclusions = array();
    private array|null $ordoGlobalArray = array();


    /**
     *
     */
    public function diagListArrayGeneralBuildOrder(
        $diagList,
        $diagCheckpoints,
        $diagThemes,
        $diagSymptoms,
        $diagConclusions,
        $ordoGlobalArray
    ) {
        $this->diagList = $diagList;
        $this->diagCheckpoints = $diagCheckpoints;
        $this->diagThemes = $diagThemes;
        $this->diagSymptoms = $diagSymptoms;
        $this->diagConclusions = $diagConclusions;
        $this->ordoGlobalArray = $ordoGlobalArray;

        $this->diagListOrganizer();
        $this->checkpointsAddition();
        $this->themesAddition();
        $this->symptomsAddition();
        $this->conclusionsAddition();
        $this->ordoAddition();

        //echo '<pre>';
        //print_r($this->diagList);
        //echo '</pre>';

        return $this->diagList;
    }


    /**
     *
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

            $tempArray['checkpoints'] = array();
            $tempArray['themes'] = array();
            $tempArray['symptoms'] = array();
            $tempArray['conclusions'] = array();

            $tempArray['ordo'] = array();

            array_push($diagListTempArray, $tempArray);
        }

        $this->diagList = $diagListTempArray;
    }


    /**
     *
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


    /**
     *
     */
    private function themesAddition()
    {
        foreach ($this->diagList as $diagKey => $diagValue) {
            foreach ($this->diagThemes as $themeKey => $themeValue) {
                if ($diagValue['diagID'] == $themeValue['diagID']) {
                    array_push($this->diagList[$diagKey]['themes'], $themeValue);
                }
            }
        }
    }


    /**
     *
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


    /**
     *
     */
    private function conclusionsAddition()
    {
        foreach ($this->diagList as $diagKey => $diagValue) {
            foreach ($this->diagConclusions as $concluKey => $concluValue) {
                if ($diagValue['diagID'] == $concluValue['diagID']) {
                    array_push($this->diagList[$diagKey]['conclusions'], $concluValue);
                }
            }
        }
    }


    /**
     *
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
