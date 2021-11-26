<?php

namespace HealthKerd\Processor\medic\care;

class CareSessionsOrganizer
{
    private array|null $careSessionsList = array();
    private array|null $careSessionElements = array();

    /**
     *
     */
    public function careSessionsGeneralBuildOrder(
        $careSessionsList,
        $careSessionElements,
    ) {
        $this->careSessionsList = $careSessionsList;
        $this->careSessionElements = $careSessionElements;

        $this->careSessionsContentOrganizer();
        $this->careElementsAddition();

        //echo '<pre>';
        //print_r($this->careSessionsList);
        //echo '</pre>';

        return $this->careSessionsList;
    }

    /**
     *
     */
    private function careSessionsContentOrganizer()
    {
        $careSessionTempArray = array();

        foreach ($this->careSessionsList as $value) {
            $tempArray = array();

            $tempArray['careSessionID'] = $value['careSessionID'];
            $tempArray['contentType'] = 'careSession';
            $tempArray['medicEventID'] = $value['medicEventID'];
            $tempArray['careUsagePhaseID'] = $value['careUsagePhaseID'];
            $tempArray['comment'] = $value['comment'];
            $tempArray['elements'] = array();

            array_push($careSessionTempArray, $tempArray);
        }

        $this->careSessionsList = $careSessionTempArray;
    }

    /**
     *
     */
    private function careElementsAddition()
    {
        foreach ($this->careSessionsList as $sessionKey => $sessionValue) {
            foreach ($this->careSessionElements as $elemKey => $elemValue) {
                if ($sessionValue['careSessionID'] ==  $elemValue['careSessionID']) {
                    array_push($this->careSessionsList[$sessionKey]['elements'], $elemValue);
                }
            }
        }
    }
}
