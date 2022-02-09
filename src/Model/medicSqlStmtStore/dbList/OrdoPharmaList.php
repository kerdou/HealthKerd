<?php

namespace HealthKerd\Model\medicSqlStmtStore\dbList;

/** Classe dédiée à la table ordo_pharma_list
 * * Sujet: Liste des ordonnances pharmaceutiques
 */
class OrdoPharmaList
{
    /** Génére la déclaration SQL des ordonnances de prélèvements pharmaceutiques pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectForEvent(string $whereString): string
    {
        $stmtStart = '
            SELECT
                ordo_pharma_list.ordoPharmaID,
                ordo_pharma_list.ordoDate,
                ordo_pharma_list.ordoComment,
                diag_list.diagID,
                medic_event_list.medicEventID
            FROM
                medic_event_list
            INNER JOIN diag_list ON medic_event_list.medicEventID = diag_list.medicEventID
            INNER JOIN ordo_pharma_list ON diag_list.diagID = ordo_pharma_list.diagID
            WHERE';
        // ajouter medic_event_list.medicEventID = 35;

        $stmtEnd = 'ORDER BY ordo_pharma_list.ordoDate;';

        return $stmtStart . $whereString . $stmtEnd;
    }
}
