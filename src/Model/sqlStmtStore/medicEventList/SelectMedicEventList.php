<?php

namespace HealthKerd\Model\sqlStmtStore\medicEventList;

/** Classe dédiée à la table medic_event_list
 * * Sujet: Liste des events médicaux
 */
class SelectMedicEventList
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
                medic_event_list.*,
                medic_event_category.name AS eventCatName,
                doc_office_list.docOfficeID,
                doc_office_list.name AS docOfficeName,
                doc_office_list.addr1 AS docOfficeAddr1,
                doc_office_list.addr2 AS docOfficeAddr2,
                doc_office_list.postCode AS docOfficePostCode,
                doc_office_list.cityName AS docOfficeCityName
            FROM
                medic_event_list
            INNER JOIN users_list ON medic_event_list.userID = users_list.userID
            INNER JOIN medic_event_category ON medic_event_list.medicEventCatID = medic_event_category.medicEventCatID
            INNER JOIN doc_office_list ON medic_event_list.docOfficeID = doc_office_list.docOfficeID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        return $stmt . $whereString . ';';
    }

    /** Récupére les ID des events à venir d'un user
     * @return string       Déclaration SQL complète
     */
    public function comingEventsIdsStmt(): string
    {
        $stmt =
            'SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                userID = ' . $_SESSION['userID'] .
            ' AND
                dateTime >= CURRENT_DATE
            ORDER BY
                dateTime;';

        return $stmt;
    }

    /** Récupére les ID tous les events d'un user
     * @return string       Déclaration SQL complète
     */
    public function eventsIdsByUserIdStmt(): string
    {
        $stmt =
            'SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                userID = ' . $_SESSION['userID'] .
            ';';

        return $stmt;
    }

    /** Récupére les ID des events d'un user par rapport à une catégorie
     * -----
     * * Requête préparée
     * @return string       Déclaration SQL complète
     */
    public function eventsIdsbyCatIdStmt(): string
    {
        $stmt =
            'SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                medicEventCatID = :medicEventCatID
            AND
                userID = :userID;';

        return $stmt;
    }

    /** Récupére les ID des events d'un user par rapport à un thème médical
     * -----
     * * Requête préparée
     * @return string       Déclaration SQL complète
     */
    public function eventsIdsFromMedicThemeIdStmt(): string
    {
        $stmt =
            'SELECT
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN medic_event_themes_relation ON medic_event_list.medicEventID = medic_event_themes_relation.medicEventID
            WHERE
                medic_event_themes_relation.medicThemeID = :medicThemeID
            AND
                medic_event_list.userID = :userID;';

        return $stmt;
    }

    /** Récupérer les IDs des events par rapports aux spé médciales utilisées
     * -----
     * * Requête préparée
     * @return string       Déclaration SQL complète
     */
    public function eventsIdsFromSpeMedicIdStmt(): string
    {
        $stmt =
            'SELECT
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN medic_event_spemedic_relation ON medic_event_list.medicEventID = medic_event_spemedic_relation.medicEventID
            WHERE
                medic_event_spemedic_relation.speMedicID = :speMedicID
            AND
                medic_event_list.userID = :userID;';

        return $stmt;
    }

    /** Récupére les ID des events d'un user par rapport à un cabinet médical
     * -----
     * * Requête préparée
     * @return string       Déclaration SQL complète
     */
    public function eventsIdsByDocOfficeIdStmt(): string
    {
        $stmt =
            'SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                docOfficeID = :docOfficeID
            AND
                userID = :userID;';

        return $stmt;
    }

    /** Récupére les ID des events d'un user par rapport à un docteur
     * -----
     * * Requête préparée
     * @return string       Déclaration SQL complète
     */
    public function eventsIdsFromOneDocIdStmt(): string
    {
        $stmt =
            'SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                docID = :docID
            AND
                userID = :userID;';

        return $stmt;
    }

    /** Récupére les infos des events d'un user par rapport à un docteur
     * -----
     * * Requête préparée
     * @return string       Déclaration SQL complète
     */
    public function eventsFromOneDocIdStmt(): string
    {
        $stmt =
            'SELECT
                *
            FROM
                medic_event_list
            WHERE
                docID = :docID
            AND
                userID = :userID;';

        return $stmt;
    }


    /** Méthode de test pour le développement, récupére un event en particulier
     * @return string       Liste des ID d'events concernés
     */
    public function onlyOneEventStmt(): string
    {
        $stmt =
            'SELECT
                medicEventID
            FROM
                medic_event_list
            WHERE
                medicEventID = 54
            ;';

        return $stmt;
    }
}
