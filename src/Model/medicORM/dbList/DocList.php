<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table doc_list
 * * Sujet: Liste des professionnels de santé
 */
class DocList
{
    /** Récupération du contenu de l'event
    * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
    * @return string               Déclaration SQL complète
    */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT
                doc_list.title AS docTitle,
                doc_list.firstName AS docFirstName,
                doc_list.lastName AS docLastName,
                doc_list_replaced.title AS replacedDocTitle,
                doc_list_replaced.firstName AS replacedDocFirstName,
                doc_list_replaced.lastName AS replacedDocLastName,
                doc_list_labo_ordo.title AS laboOrdoDocTitle,
                doc_list_labo_ordo.firstName AS laboOrdoDocFirstName,
                doc_list_labo_ordo.lastName AS laboOrdoDocLastName,
                doc_list_replaced_labo_ordo.title AS replacedLaboOrdoDocTitle,
                doc_list_replaced_labo_ordo.firstName AS replacedLaboOrdoDocFirstName,
                doc_list_replaced_labo_ordo.lastName AS replacedLaboOrdoDocLastName,
            FROM
                medic_event_list
            INNER JOIN users_list ON medic_event_list.userID = users_list.userID
            INNER JOIN doc_list ON medic_event_list.docID = doc_list.docID
            INNER JOIN doc_list AS doc_list_replaced ON medic_event_list.replacedDocID  = doc_list_replaced.docID
            INNER JOIN doc_list AS doc_list_labo_ordo ON medic_event_list.laboOrdoDocID = doc_list_labo_ordo.docID
            INNER JOIN doc_list AS doc_list_replaced_labo_ordo ON medic_event_list.laboOrdoReplacedDocDiagID = doc_list_replaced_labo_ordo.docID
            INNER JOIN doc_office_list ON medic_event_list.docOfficeID = doc_office_list.docOfficeID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35 OR ...;

        return $stmt . $whereString . ';';
    }


    /** Récupération des données du docteur présent lors de l'event.
    * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
    * @return string               Déclaration SQL complète
    */
    public function stmtSelectDistinctAttendedDocForEvent(string $whereString)
    {
        $stmtStart = '
            SELECT DISTINCT
                medic_event_list.docID,
                doc_list.docID,
                doc_list.title,
                doc_list.firstName,
                doc_list.lastName,
                doc_list.isMyMainDoc,
                doc_list.canVisitHome,
                doc_list.isRetired,
                doc_list.isBlacklisted
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.docID = doc_list.docID
            WHERE';

        // ajouter medic_event_list.medicEventID = 35 OR ...;

        $stmtEnd = '
            ORDER BY
                medic_event_list.docID
            ;';

        return $stmtStart . $whereString . $stmtEnd;
    }


    /** Récupération des données du docteur remplacé lors de l'event.
    * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
    * @return string               Déclaration SQL complète
    */
    public function stmtSelectDistinctReplacedDocForEvent(string $whereString)
    {
        $stmtStart = '
            SELECT DISTINCT
                medic_event_list.replacedDocID,
                doc_list.docID,
                doc_list.title,
                doc_list.firstName,
                doc_list.lastName,
                doc_list.isMyMainDoc,
                doc_list.canVisitHome,
                doc_list.isRetired,
                doc_list.isBlacklisted
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.replacedDocID = doc_list.docID
            WHERE';

        $stmtEnd = '
            HAVING
                medic_event_list.replacedDocID != 0
            ORDER BY
                medic_event_list.replacedDocID
            ;';
        // ajouter medic_event_list.medicEventID = 35 OR ...;

        return $stmtStart . $whereString . $stmtEnd;
    }


    /** Récupération des données du docteur ayant fait la prescription de prélèvement en laboratoire lié à cet event
    * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
    * @return string               Déclaration SQL complète
    */
    public function stmtSelectDistinctLaboOrdoDocForEvent(string $whereString)
    {
        $stmtStart = '
            SELECT DISTINCT
                medic_event_list.laboOrdoDocID,
                doc_list.docID,
                doc_list.title,
                doc_list.firstName,
                doc_list.lastName,
                doc_list.isMyMainDoc,
                doc_list.canVisitHome,
                doc_list.isRetired,
                doc_list.isBlacklisted
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.laboOrdoDocID = doc_list.docID
            WHERE';

        // ajouter medic_event_list.medicEventID = 35 OR ...;

        $stmtEnd = '
            HAVING
                medic_event_list.laboOrdoDocID != 0
            ORDER BY
                medic_event_list.laboOrdoDocID
            ;';

        return $stmtStart . $whereString . $stmtEnd;
    }


    /** Récupération des données du docteur remplacé lors de la prescription de prélèvement en laboratoire lié à cet event
    * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
    * @return string               Déclaration SQL complète
    */
    public function stmtSelectDistinctLaboOrdoReplacedDocForEvent(string $whereString)
    {
        $stmtStart = '
            SELECT DISTINCT
                medic_event_list.laboOrdoReplacedDocDiagID,
                doc_list.docID,
                doc_list.title,
                doc_list.firstName,
                doc_list.lastName,
                doc_list.isMyMainDoc,
                doc_list.canVisitHome,
                doc_list.isRetired,
                doc_list.isBlacklisted
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.laboOrdoReplacedDocDiagID = doc_list.docID
            WHERE';

        // ajouter medic_event_list.medicEventID = 35 OR ...;

        $stmtEnd = '
            HAVING
                medic_event_list.laboOrdoReplacedDocDiagID != 0
            ORDER BY
                medic_event_list.laboOrdoReplacedDocDiagID
            ;';

        return $stmtStart . $whereString . $stmtEnd;
    }
}
