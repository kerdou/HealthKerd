<?php

namespace HealthKerd\Model\medicSqlStmtStore;

/** Stockage des templates de requêtes SQL dédiées à la partie médicale
*/
class MedicSqlStmtStore
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
        $this->MedicEventList = new \HealthKerd\Model\medicSqlStmtStore\dbList\MedicEventList();
        $this->MedicEventAffectsRelation = new \HealthKerd\Model\medicSqlStmtStore\dbList\MedicEventAffectsRelation();
        $this->MedicEventSpemedicRelation = new \HealthKerd\Model\medicSqlStmtStore\dbList\MedicEventSpemedicRelation();
        $this->MedicEventThemesRelation = new \HealthKerd\Model\medicSqlStmtStore\dbList\MedicEventThemesRelation();

        // docteurs et spécialités médicales
        $this->DocList = new \HealthKerd\Model\medicSqlStmtStore\dbList\DocList();
        $this->DocSpemedicRelation = new \HealthKerd\Model\medicSqlStmtStore\dbList\DocSpemedicRelation();
        $this->SpeMedicFullList = new \HealthKerd\Model\medicSqlStmtStore\dbList\SpeMedicFullList();

        // diagnostics
        $this->DiagList = new \HealthKerd\Model\medicSqlStmtStore\dbList\DiagList();
        $this->DiagCheckPoints = new \HealthKerd\Model\medicSqlStmtStore\dbList\DiagCheckPoints();
        $this->DiagConclusions = new \HealthKerd\Model\medicSqlStmtStore\dbList\DiagConclusions();
        $this->DiagSymptoms = new \HealthKerd\Model\medicSqlStmtStore\dbList\DiagSymptoms();
        $this->DiagMedicThemeRelations = new \HealthKerd\Model\medicSqlStmtStore\dbList\DiagMedicThemeRelations();

        // ordo pharma
        $this->OrdoPharmaList = new \HealthKerd\Model\medicSqlStmtStore\dbList\OrdoPharmaList();
        $this->PrescPharmaList = new \HealthKerd\Model\medicSqlStmtStore\dbList\PrescPharmaList();

        // ordo optique
        $this->OrdoSightList = new \HealthKerd\Model\medicSqlStmtStore\dbList\OrdoSightList();

        // ordo labo
        $this->OrdoLaboList = new \HealthKerd\Model\medicSqlStmtStore\dbList\OrdoLaboList();
        $this->PrescLaboList = new \HealthKerd\Model\medicSqlStmtStore\dbList\PrescLaboList();
        $this->PrescLaboElements = new \HealthKerd\Model\medicSqlStmtStore\dbList\PrescLaboElements();
        $this->OrdoLaboSlots = new \HealthKerd\Model\medicSqlStmtStore\dbList\OrdoLaboSlots();

        // ordo vax
        $this->OrdoVaxList = new \HealthKerd\Model\medicSqlStmtStore\dbList\OrdoVaxList();
        $this->PrescVaxList = new \HealthKerd\Model\medicSqlStmtStore\dbList\PrescVaxList();
        $this->OrdoVaxSlots = new \HealthKerd\Model\medicSqlStmtStore\dbList\OrdoVaxSlots();

        // sessions de soin
        $this->CareSessionsList = new \HealthKerd\Model\medicSqlStmtStore\dbList\CareSessionsList();
        $this->CareSessionElements = new \HealthKerd\Model\medicSqlStmtStore\dbList\CareSessionElements();

        // sessions de vaccination
        $this->VaxSessionsList = new \HealthKerd\Model\medicSqlStmtStore\dbList\VaxSessionsList();
        $this->VaxSessionsSideEffects = new \HealthKerd\Model\medicSqlStmtStore\dbList\VaxSessionsSideEffects();
    }

    public function __destruct()
    {
    }
}
