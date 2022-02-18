<?php

namespace HealthKerd\Model\sqlStmtStore\docList;

/** Classe dédiée à la table doc_list
 * * Sujet: Liste des professionnels de santé
 */
class SelectDocList
{
    public function __destruct()
    {
    }

    /** Récupération du contenu de l'event
    * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
    * @return string               Déclaration SQL complète
    */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmt =
            'SELECT
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
    public function stmtSelectDistinctAttendedDocForEvent(string $whereString): string
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
    public function stmtSelectDistinctReplacedDocForEvent(string $whereString): string
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
    public function stmtSelectDistinctLaboOrdoDocForEvent(string $whereString): string
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
    public function stmtSelectDistinctLaboOrdoReplacedDocForEvent(string $whereString): string
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

    /** Récupération des données basiques de tous les docteurs d'un user
     * * docID
     * * Titre du doc
     * * Prénom du doc
     * * Nom de famille du doc
     * @param string $whereString   Indique la colonne recherche et la clé du user
     * @return string               Déclaration SQL complète
     */
    public function stmtAllDocsBasicsInfos(string $whereString): string
    {
        $stmtStart =
            'SELECT
                docID, title, firstName, lastName
            FROM
                doc_list
            WHERE';
        // ajouter doc_list.userID = 1
        $stmtEnd =
            'ORDER BY
                lastName;
            ';

        return $stmtStart . $whereString . $stmtEnd;
    }

    /** Récupération de toutes les données d'un docteur depuis doc_list
     * ------
     * Requête préparée
     * @return string       Déclaration SQL complète
     */
    public function selectPrepAllDataFromOneDocStmt(): string
    {
        $stmt =
            'SELECT
                *
            FROM
                doc_list
            WHERE
                docID = :docID
            HAVING
                userID = :userID;';

        return $stmt;
    }

    /** Récupération de l'ID du dernier docteur créé par le user
     * TODO: Trouver une meilleure méthode
     * @return string   Le docID
    */
    public function getNewDocIDStmt(): string
    {
        $stmt =
            'SELECT
                docID
            FROM
                doc_list
            WHERE ' . $_SESSION['userID'] .
            ' ORDER BY docID
            DESC LIMIT 1;';

        return $stmt;
    }
}
