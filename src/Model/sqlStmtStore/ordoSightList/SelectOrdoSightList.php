<?php

namespace HealthKerd\Model\sqlStmtStore\ordoSightList;

/** Classe dédiée à la table ordo_sight_list
 * * Sujet: Liste des ordonnances optique, lunettes et lentilles confondues
 */
class SelectOrdoSightList
{
    public function __destruct()
    {
    }

    /** Génére la déclaration SQL des ordonnances de prélèvements pharmaceutiques pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmtStart =
            'SELECT
                ordo_sight_list.*,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_sight_list ON diag_list.diagID = ordo_sight_list.diagID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        $stmtEnd = 'ORDER BY ordo_sight_list.date;';

        /*
            SELECT
                ordo_sight_list.*,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_sight_list ON diag_list.diagID = ordo_sight_list.diagID
            WHERE
                medic_event_list.userID = 1
            ORDER BY
                ordo_sight_list.date
            ;
        */

        return $stmtStart . $whereString . $stmtEnd;
    }
}
