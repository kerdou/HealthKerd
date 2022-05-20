<?php

namespace HealthKerd\Processor\medic\event\vax;

/** Gestion des sessions de vaccination
*/
class VaxSessionsOrganizer
{
    private array $vaxSessionsList = array();
    private array $vaxSessionsSideEffects = array();
    private array $vaxSessionsOrdoSlots = array();

    public function __destruct()
    {
    }

    /** Ordre de modification des sessions de vaccination
     * TODO: Ajouter la gestion des slots de vaccination
     * @param array $vaxSessionsList            Liste des sessions de vaccination
     * @param array $vaxSessionsSideEffects     Liste des effets secondaires rencontrés après vaccination
     * @param array $vaxSessionsOrdoSlots       Liste des slots d'ordonnance de vaccination concernées
     * @return array                            Données modifiées
     */
    public function vaxSessionsGeneralBuildOrder(
        array $vaxSessionsList,
        array $vaxSessionsSideEffects,
        array $vaxSessionsOrdoSlots
    ) {
        $this->vaxSessionsList = $vaxSessionsList;
        $this->vaxSessionsSideEffects = $vaxSessionsSideEffects;
        $this->vaxSessionsOrdoSlots = $vaxSessionsOrdoSlots;

        $this->vaxSessionsContentOrganizer();
        $this->vaxSideEffectsAddition();

        return $this->vaxSessionsList;
    }

    /** Réorganisation des données des sessions de vaccination
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

    /** Ajout des effets secondaires rencontrés aprés vaccination
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
