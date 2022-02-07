<?php

namespace HealthKerd\Model\medicORM\dbList;

/** Classe dédiée à la table medic_event_list
 * * Sujet: Liste des events médicaux
 */
class MedicEventList
{
    /** Récupération du contenu de l'event
    * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
    * @return string               Déclaration SQL complète
    */
    public function stmtSelectForEvent(string $whereString)
    {
        $stmt = '
            SELECT
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
}
