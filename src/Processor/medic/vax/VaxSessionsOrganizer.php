<?php

namespace HealthKerd\Processor\medic\vax;

class VaxSessionsOrganizer
{
    private array|null $vaxSessionsList = array();
    private array|null $vaxSessionsSideEffects = array();

    /**
     *
     */
    public function vaxSessionsGeneralBuildOrder(
        $vaxSessionsList,
        $vaxSessionsSideEffects,
    ) {
        $this->vaxSessionsList = $vaxSessionsList;
        $this->vaxSessionsSideEffects = $vaxSessionsSideEffects;

        $this->vaxSessionsContentOrganizer();
        $this->vaxSideEffectsAddition();

        //echo '<pre>';
        //print_r($this->vaxSessionsList);
        //echo '</pre>';

        return $this->vaxSessionsList;
    }

    /**
     *
     */
    private function vaxSessionsContentOrganizer()
    {
        $vaxSessionTempArray = array();

        foreach ($this->vaxSessionsList as $value) {
            $tempArray = array();

            $tempArray['vaxSessionID'] = $value['vaxSessionID'];
            $tempArray['contentType'] = 'vaxSession';
            $tempArray['medicEventID'] = $value['medicEventID'];
            $tempArray['vaxUsagePhaseID'] = $value['vaxUsagePhaseID'];
            $tempArray['armUsed'] = $value['armUsed'];
            $tempArray['comment'] = $value['comment'];
            $tempArray['sideEffects'] = array();

            array_push($vaxSessionTempArray, $tempArray);
        }

        $this->vaxSessionsList = $vaxSessionTempArray;
    }

    /**
     *
     */
    private function vaxSideEffectsAddition()
    {
        foreach ($this->vaxSessionsList as $sessionKey => $sessionValue) {
            foreach ($this->vaxSessionsSideEffects as $sideKey => $sideValue) {
                if ($sessionValue['vaxSessionID'] ==  $sideValue['vaxSessionID']) {
                    array_push($this->vaxSessionsList[$sessionKey]['sideEffects'], $sideValue);
                }
            }
        }
    }
}
