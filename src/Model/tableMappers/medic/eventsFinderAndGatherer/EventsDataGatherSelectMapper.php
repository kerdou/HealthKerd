<?php

namespace HealthKerd\Model\tableMappers\medic\eventsFinderAndGatherer;

/** Stockage des templates de requêtes SQL dédiées au Select d'events médicaux
*/
class EventsDataGatherSelectMapper
{
    public array $mapList = array();

    public function __construct()
    {
        // events
        $this->mapList['MedicEventList'] = new \HealthKerd\Model\sqlStmtStore\medicEventList\SelectMedicEventList();
        $this->mapList['MedicEventAffectsRelation'] = new \HealthKerd\Model\sqlStmtStore\medicEventAffectsRelation\SelectMedicEventAffectsRelation();
        $this->mapList['MedicEventSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\medicEventSpemedicRelation\SelectMedicEventSpemedicRelation();
        $this->mapList['MedicEventThemesRelation'] = new \HealthKerd\Model\sqlStmtStore\medicEventThemesRelation\SelectMedicEventThemesRelation();

        // docteurs et spécialités médicales
        $this->mapList['DocList'] = new \HealthKerd\Model\sqlStmtStore\docList\SelectDocList();
        $this->mapList['DocSpemedicRelation'] = new \HealthKerd\Model\sqlStmtStore\docSpemedicRelation\SelectDocSpemedicRelation();
        $this->mapList['SpeMedicFullList'] = new \HealthKerd\Model\sqlStmtStore\speMedicFullList\SelectSpeMedicFullList();

        // diagnostics
        $this->mapList['DiagList'] = new \HealthKerd\Model\sqlStmtStore\diagList\SelectDiagList();
        $this->mapList['DiagCheckPoints'] = new \HealthKerd\Model\sqlStmtStore\diagCheckPoints\SelectDiagCheckPoints();
        $this->mapList['DiagConclusions'] = new \HealthKerd\Model\sqlStmtStore\diagConclusions\SelectDiagConclusions();
        $this->mapList['DiagSymptoms'] = new \HealthKerd\Model\sqlStmtStore\diagSymptoms\SelectDiagSymptoms();
        $this->mapList['DiagMedicThemeRelations'] = new \HealthKerd\Model\sqlStmtStore\diagMedicThemeRelations\SelectDiagMedicThemeRelations();

        // ordo pharma
        $this->mapList['OrdoPharmaList'] = new \HealthKerd\Model\sqlStmtStore\ordoPharmaList\SelectOrdoPharmaList();
        $this->mapList['PrescPharmaList'] = new \HealthKerd\Model\sqlStmtStore\prescPharmaList\SelectPrescPharmaList();

        // ordo optique
        $this->mapList['OrdoSightList'] = new \HealthKerd\Model\sqlStmtStore\ordoSightList\SelectOrdoSightList();

        // ordo labo
        $this->mapList['OrdoLaboList'] = new \HealthKerd\Model\sqlStmtStore\ordoLaboList\SelectOrdoLaboList();
        $this->mapList['PrescLaboList'] = new \HealthKerd\Model\sqlStmtStore\prescLaboList\SelectPrescLaboList();
        $this->mapList['PrescLaboElements'] = new \HealthKerd\Model\sqlStmtStore\prescLaboElements\SelectPrescLaboElements();
        $this->mapList['OrdoLaboSlots'] = new \HealthKerd\Model\sqlStmtStore\ordoLaboSlots\SelectOrdoLaboSlots();

        // ordo vax
        $this->mapList['OrdoVaxList'] = new \HealthKerd\Model\sqlStmtStore\ordoVaxList\SelectOrdoVaxList();
        $this->mapList['PrescVaxList'] = new \HealthKerd\Model\sqlStmtStore\prescVaxList\SelectPrescVaxList();
        $this->mapList['OrdoVaxSlots'] = new \HealthKerd\Model\sqlStmtStore\ordoVaxSlots\SelectOrdoVaxSlots();

        // sessions de soin
        $this->mapList['CareSessionsList'] = new \HealthKerd\Model\sqlStmtStore\careSessionsList\SelectCareSessionsList();
        $this->mapList['CareSessionElements'] = new \HealthKerd\Model\sqlStmtStore\careSessionElements\SelectCareSessionElements();

        // sessions de vaccination
        $this->mapList['VaxSessionsList'] = new \HealthKerd\Model\sqlStmtStore\vaxSessionsList\SelectVaxSessionsList();
        $this->mapList['VaxSessionsSideEffects'] = new \HealthKerd\Model\sqlStmtStore\vaxSessionsSideEffects\SelectVaxSessionsSideEffects();
    }

    public function __destruct()
    {
    }
}
