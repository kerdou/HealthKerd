<?php

namespace HealthKerd\Processor\medic\event\care;

/** Gestion des sessions de soin
*/
class CareSessionsOrganizer
{
    private array|null $careSessionsList = array();
    private array|null $careSessionElements = array();

    /** Ordre de modification des sessions de soin
     * @param array $careSessionsList       Liste des sessions de soin
     * @param array $careSessionElements    Eléments des sessions de soin
     * @return array                        Données modifiées
     */
    public function careSessionsGeneralBuildOrder(
        array $careSessionsList,
        array $careSessionElements,
    ) {
        $this->careSessionsList = $careSessionsList;
        $this->careSessionElements = $careSessionElements;

        $this->careSessionsContentOrganizer();
        $this->careElementsAddition();

        //echo '<pre>';
        //    print_r($this->careSessionsList);
        //echo '</pre>';

        return $this->careSessionsList;
    }

    /** Réorganisation des données des sessions de soin
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

    /** Ajout des éléments de session de soin
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
