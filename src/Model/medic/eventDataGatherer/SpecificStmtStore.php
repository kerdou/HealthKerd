<?php

namespace HealthKerd\Model\medic\eventDataGatherer;

/**  */
abstract class SpecificStmtStore extends EventDataHelpers
{

    public function __construct()
    {
        parent::__construct();
    }

    /** */
    protected function medicEventList(array $eventsIdList)
    {
        if (sizeof($eventsIdList) > 0) {
            $whereString = $this->stmtWhereBuilder($eventsIdList, 'medicEventID');

            $stmt =
                'SELECT
                    medic_event_list.*,
                    users_list.firstName AS userFirstName, users_list.lastName AS userLastName,
                    medic_event_category.name AS eventCatName,
                    doc_list.firstName AS docFirstName, doc_list.lastName AS docLastName,
                    doc_list_substit.firstName AS substitDocFirstName, doc_list_substit.lastName AS substitDocLastName,
                    doc_list_labo_ordo.firstName AS laboOrdoDocFirstName, doc_list_labo_ordo.lastName AS laboOrdoDocLastName,
                    doc_list_labo_ordo_substit.firstName AS substitLaboOrdoDocFirstName, doc_list_labo_ordo_substit.lastName AS substitLaboOrdoDocLastName,
                    doc_office_list.name AS docOfficeName
                FROM
                    medic_event_list
                INNER JOIN users_list ON medic_event_list.userID = users_list.userID
                INNER JOIN medic_event_category ON medic_event_list.medicEventCatID = medic_event_category.medicEventCatID
                INNER JOIN doc_list ON medic_event_list.docID = doc_list.docID
                INNER JOIN doc_list AS doc_list_substit ON medic_event_list.replacedDocID  = doc_list_substit.docID
                INNER JOIN doc_list AS doc_list_labo_ordo ON medic_event_list.laboOrdoDocID = doc_list_labo_ordo.docID
                INNER JOIN doc_list AS doc_list_labo_ordo_substit ON medic_event_list.laboOrdoReplacedDocDiagID = doc_list_labo_ordo_substit.docID
                INNER JOIN doc_office_list ON medic_event_list.docOfficeID = doc_office_list.docOfficeID
                WHERE
                    ' . $whereString . '
                ; ';

            $this->dataStore['medic_event_list']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['medic_event_list']['pdoStmt'], 'medic_event_list/pdoResult');
        }
    }


    /** */
    protected function docList(array $docID)
    {
        if (sizeof($docID) > 0) {
            $whereString = $this->stmtWhereBuilder($docID, 'docID');

            $stmt = 'SELECT
                        doc_list.docID,
                        doc_list.isMyMainDoc,
                        doc_list.canVisitHome,
                        doc_list.isRetired,
                        doc_list.isBlacklisted,
                        doc_list.title,
                        doc_list.lastName,
                        doc_list.firstName
                    FROM
                        doc_list
                    WHERE ' . $whereString . '
                    ; ';

            $this->dataStore['doc_list']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['doc_list']['pdoStmt'], 'doc_list/pdoResult');
        }
    }


    /** */
    protected function docOfficeList(array $docOfficeID)
    {
        if (sizeof($docOfficeID) > 0) {
            $whereString = $this->stmtWhereBuilder($docOfficeID, 'docOfficeID');

            $stmt = 'SELECT
                        doc_office_list.docOfficeID,
                        doc_office_list.name,
                        doc_office_list.addr1,
                        doc_office_list.addr2,
                        doc_office_list.postCode,
                        doc_office_list.cityName
                    FROM
                        doc_office_list
                    WHERE ' . $whereString . '
                    ; ';

            $this->dataStore['doc_office_list']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['doc_office_list']['pdoStmt'], 'doc_office_list/pdoResult');
        }
    }

    protected function prescPharmaListOnlyOrdoPharmaIds(array $prescPharmaID)
    {
        if (sizeof($prescPharmaID) > 0) {
            $whereString = $this->stmtWhereBuilder($prescPharmaID, 'prescPharmaID');

            $stmt = 'SELECT
                        presc_pharma_list.prescPharmaID,
                        presc_pharma_list.ordoPharmaID
                    FROM
                        presc_pharma_list
                    WHERE ' . $whereString . '
                    ; ';
            $this->dataStore['presc_pharma_list']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['presc_pharma_list']['pdoStmt'], 'doc_office_list/pdoResult');
        }
    }



    protected function ordoVaxSlotsOnlyOrdoVaxIds(array $ordoVaxID)
    {
        if (sizeof($ordoVaxID) > 0) {
            $whereString = $this->stmtWhereBuilder($ordoVaxID, 'ordoVaxID');

            $stmt = 'SELECT
                        presc_vax_list.ordoVaxID
                    FROM
                        presc_vax_list
                    WHERE ' . $whereString . '
                    ; ';
            $this->dataStore['presc_vax_list']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['presc_vax_list']['pdoStmt'], 'presc_vax_list/pdoResult');
        }
    }


    protected function treatPharmaNames(array $treatPharmaID)
    {
        if (sizeof($treatPharmaID) > 0) {
            $whereString = $this->stmtWhereBuilder($treatPharmaID, 'treatPharmaID');

            $stmt = 'SELECT
                        treat_pharma_list.treatPharmaID,
                        treat_pharma_list.name
                    FROM
                        treat_pharma_list
                    WHERE ' . $whereString . '
                    ; ';
            $this->dataStore['treat_pharma_list']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['treat_pharma_list']['pdoStmt'], 'treat_pharma_list/pdoResult');
        }
    }

    protected function medicAffectNames(array $medicAffectID)
    {
        if (sizeof($medicAffectID) > 0) {
            $whereString = $this->stmtWhereBuilder($medicAffectID, 'medicAffectID');

            $stmt = 'SELECT
                        medic_affect_list.medicAffectID,
                        medic_affect_list.name
                    FROM
                        medic_affect_list
                    WHERE ' . $whereString . '
                    ; ';
            $this->dataStore['medic_affect_list']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['medic_affect_list']['pdoStmt'], 'medic_affect_list/pdoResult');
        }
    }

    /** */
    protected function medicEventAffectsRelation(array $eventsIdList)
    {
        if (sizeof($eventsIdList) > 0) {
            $whereString = $this->stmtWhereBuilder($eventsIdList, 'medicEventID');
            $stmt = 'SELECT
                        medic_event_affects_relation.*,
                        medic_affect_list.name AS medicAffectName
                    FROM
                        medic_event_affects_relation
                    INNER JOIN medic_affect_list ON medic_event_affects_relation.medicAffectID = medic_affect_list.medicAffectID
                    WHERE
                        ' . $whereString . '
                    ; ';
            $this->dataStore['medic_event_affects_relation']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['medic_event_affects_relation']['pdoStmt'], 'medic_event_affects_relation/pdoResult');
        }
    }


    /** */
    protected function medicEventThemesRelation(array $eventsIdList)
    {
        if (sizeof($eventsIdList) > 0) {
            $whereString = $this->stmtWhereBuilder($eventsIdList, 'medicEventID');
            $stmt = 'SELECT
                        medic_event_themes_relation.*,
                        medic_theme_list.name AS medicThemeName
                    FROM
                        medic_event_themes_relation
                    INNER JOIN medic_theme_list ON medic_event_themes_relation.medicThemeID = medic_theme_list.medicThemeID
                    WHERE
                        ' . $whereString . '
                    ; ';
            $this->dataStore['medic_event_themes_relation']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['medic_event_themes_relation']['pdoStmt'], 'medic_event_themes_relation/pdoResult');
        }
    }


    /** */
    protected function medicEventSpemedicRelation(array $eventsIdList)
    {
        if (sizeof($eventsIdList) > 0) {
            $whereString = $this->stmtWhereBuilder($eventsIdList, 'medicEventID');
            $stmt = 'SELECT
                        medic_event_spemedic_relation.*,
                        spe_medic_full_list.name AS speMedicName
                    FROM
                        medic_event_spemedic_relation
                    INNER JOIN spe_medic_full_list ON medic_event_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
                    WHERE
                        ' . $whereString . '
                    ; ';
            $this->dataStore['medic_event_spemedic_relation']['pdoStmt'] = $stmt;
            $this->pdoStmtAndDestInsertionInCue($this->dataStore['medic_event_spemedic_relation']['pdoStmt'], 'medic_event_spemedic_relation/pdoResult');
        }
    }
}
