<?php

namespace HealthKerd\Processor\medic\diag;

class DiagConclusionsOrganizer
{
    private array|null $diagConclusions = array();
    private array|null $medicAffectList = array();

    /**
     *
     */
    public function diagConclusionsGeneralBuildOrder(
        $diagConclusions,
        $medicAffectList
    ) {
        $this->diagConclusions = $diagConclusions;
        $this->medicAffectList = $medicAffectList;

        $this->diagConclusionsOrganizer();
        $this->diagConclusionsMerge();

        //echo '<pre>';
        //print_r($this->diagConclusions);
        //print_r($this->medicAffectList);
        //echo '</pre>';

        return $this->diagConclusions;
    }


    /**
     *
     */
    private function diagConclusionsOrganizer()
    {
        $conclusionTempArray = array();

        foreach ($this->diagConclusions as $value) {
            $tempArray = array();

            $tempArray['diagConclusionID'] = $value['diagConclusionID'];
            $tempArray['diagID'] = $value['diagID'];
            $tempArray['medicAffectID'] = $value['medicAffectID'];
            $tempArray['affectName'] = '';

            $tempArray['sickStatus']['general'] = $value['sickGeneralStatus'];
            $tempArray['sickStatus']['detailed'] = $value['sickDetailedStatus'];

            $tempArray['dateTime'] = $value['dateTime'];

            array_push($conclusionTempArray, $tempArray);
        }

        $this->diagConclusions = $conclusionTempArray;
    }


    /**
     *
     */
    private function diagConclusionsMerge()
    {
        foreach ($this->diagConclusions as $concluKey => $concluValue) {
            foreach ($this->medicAffectList as $affectKey => $affectValue) {
                if ($concluValue['medicAffectID'] == $affectValue['medicAffectID']) {
                    $this->diagConclusions[$concluKey]['affectName'] = $affectValue['name'];
                }
            }
        }
    }
}
