<?php

namespace HealthKerd\Model\sqlStmtStore\docofficeSpemedicRelation;

/** Classe dédiée à la table docoffice_spemedic_relation
 * * Sujet: Relation entre les cabinets médicau et leurs spécialités médicales
 */
class SelectDocofficeSpemedicRelation
{
    public function __destruct()
    {
    }

    /** Récuperer les spécialités médicales de tous les cabinets médicaux créés par un user
     * @return string           Déclaration SQL compléte
     */
    public function selectEverySpeMedicOfAllDocOfficesOfUser(): string
    {
        $stmtStart =
            'SELECT DISTINCT
                docoffice_spemedic_relation.*,
                spe_medic_full_list.name
            FROM doc_office_list
            INNER JOIN docoffice_spemedic_relation ON doc_office_list.docOfficeID = docoffice_spemedic_relation.docOfficeID
            INNER JOIN spe_medic_full_list ON docoffice_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE doc_office_list.userID = ';

        $stmtEnd = ' ORDER BY spe_medic_full_list.name;';

        return $stmtStart . $_SESSION['userID'] . $stmtEnd;
    }
}
