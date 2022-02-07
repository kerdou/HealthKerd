<?php

namespace HealthKerd\Model\medicORM;

/** ORM personnalisé dédié à la partie médicale
*/
class MedicORM
{
    // events
    public object $MedicEventAffectsRelation;
    public object $MedicEventList;
    public object $MedicEventSpemedicRelation;
    public object $MedicEventThemesRelation;

    // docteurs et spécialités médicales
    public object $DocList;
    public object $DocSpemedicRelation;
    public object $SpeMedicFullList;

    // diagnostics
    public object $DiagCheckPoints;
    public object $DiagConclusions;
    public object $DiagList;
    public object $DiagMedicThemeRelations;
    public object $DiagSymptoms;

    // ordo pharma
    public object $OrdoPharmaList;
    public object $PrescPharmaList;

    // ordo optique
    public object $OrdoSightList;

    // ordo labo
    public object $OrdoLaboList;
    public object $PrescLaboList;
    public object $PrescLaboElements;
    public object $OrdoLaboSlots;

    // ordo vax
    public object $OrdoVaxList;
    public object $PrescVaxList;
    public object $OrdoVaxSlots;

    // sessions de vaccination
    public object $VaxSessionsList;
    public object $VaxSessionsSideEffects;

    /** Instanciation de toutes les classes de tables
    */
    public function __construct()
    {
        // events
        $this->MedicEventList = new \HealthKerd\Model\medicORM\dbList\MedicEventList();
        $this->MedicEventAffectsRelation = new \HealthKerd\Model\medicORM\dbList\MedicEventAffectsRelation();
        $this->MedicEventSpemedicRelation = new \HealthKerd\Model\medicORM\dbList\MedicEventSpemedicRelation();
        $this->MedicEventThemesRelation = new \HealthKerd\Model\medicORM\dbList\MedicEventThemesRelation();

        // docteurs et spécialités médicales
        $this->DocList = new \HealthKerd\Model\medicORM\dbList\DocList();
        $this->DocSpemedicRelation = new \HealthKerd\Model\medicORM\dbList\DocSpemedicRelation();
        $this->SpeMedicFullList = new \HealthKerd\Model\medicORM\dbList\SpeMedicFullList();

        // diagnostics
        $this->DiagList = new \HealthKerd\Model\medicORM\dbList\DiagList();
        $this->DiagCheckPoints = new \HealthKerd\Model\medicORM\dbList\DiagCheckPoints();
        $this->DiagConclusions = new \HealthKerd\Model\medicORM\dbList\DiagConclusions();
        $this->DiagSymptoms = new \HealthKerd\Model\medicORM\dbList\DiagSymptoms();
        $this->DiagMedicThemeRelations = new \HealthKerd\Model\medicORM\dbList\DiagMedicThemeRelations();

        // ordo pharma
        $this->OrdoPharmaList = new \HealthKerd\Model\medicORM\dbList\OrdoPharmaList();
        $this->PrescPharmaList = new \HealthKerd\Model\medicORM\dbList\PrescPharmaList();

        // ordo optique
        $this->OrdoSightList = new \HealthKerd\Model\medicORM\dbList\OrdoSightList();

        // ordo labo
        $this->OrdoLaboList = new \HealthKerd\Model\medicORM\dbList\OrdoLaboList();
        $this->PrescLaboList = new \HealthKerd\Model\medicORM\dbList\PrescLaboList();
        $this->PrescLaboElements = new \HealthKerd\Model\medicORM\dbList\PrescLaboElements();
        $this->OrdoLaboSlots = new \HealthKerd\Model\medicORM\dbList\OrdoLaboSlots();

        // ordo vax
        $this->OrdoVaxList = new \HealthKerd\Model\medicORM\dbList\OrdoVaxList();
        $this->PrescVaxList = new \HealthKerd\Model\medicORM\dbList\PrescVaxList();
        $this->OrdoVaxSlots = new \HealthKerd\Model\medicORM\dbList\OrdoVaxSlots();

        // sessions de soin
        $this->CareSessionsList = new \HealthKerd\Model\medicORM\dbList\CareSessionsList();
        $this->CareSessionElements = new \HealthKerd\Model\medicORM\dbList\CareSessionElements();

        // sessions de vaccination
        $this->VaxSessionsList = new \HealthKerd\Model\medicORM\dbList\VaxSessionsList();
        $this->VaxSessionsSideEffects = new \HealthKerd\Model\medicORM\dbList\VaxSessionsSideEffects();
    }

    public function __destruct()
    {
    }
}
