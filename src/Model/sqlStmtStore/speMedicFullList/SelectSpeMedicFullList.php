<?php

namespace HealthKerd\Model\sqlStmtStore\speMedicFullList;

/** Classe dédiée à la table spe_medic_full_list
 * * Sujet: Liste des spécialités médicales
 */
class SelectSpeMedicFullList
{
    public function __destruct()
    {
    }

    /** Génére la déclaration SQL des spécialités médicales pour chaque docteur pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectDistinctForAttendedDocsOnEvent(string $whereString): string
    {
        $stmtStart =
            'SELECT DISTINCT
                doc_list.docID,
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.docID = doc_list.docID
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE';
        // ajouter medic_event_list.userID = 1 ou medic_event_list.medicEventID = 35

        $stmtEnd = '
            HAVING
                doc_list.docID != 0
            ORDER BY
                doc_list.docID,
                spe_medic_full_list.name
            ;';

        return $stmtStart . $whereString . $stmtEnd;
    }

    /** Génére la déclaration SQL des spécialités médicales pour chaque docteur pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectDistinctForReplacedDocsOnEvent(string $whereString): string
    {
        $stmtStart =
            'SELECT DISTINCT
                medic_event_list.replacedDocID AS docID,
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.replacedDocID = doc_list.docID
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE';
        // ajouter medic_event_list.userID = 1 ou medic_event_list.medicEventID = 35

        $stmtEnd = '
            HAVING
                medic_event_list.replacedDocID != 0
            ORDER BY
                medic_event_list.replacedDocID,
                spe_medic_full_list.name
            ;';

        /*
            SELECT DISTINCT
                medic_event_list.replacedDocID AS docID,
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.replacedDocID = doc_list.docID
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE
                medic_event_list.userID = 1
            HAVING
                medic_event_list.replacedDocID != 0
            ORDER BY
                medic_event_list.replacedDocID,
                spe_medic_full_list.name
            ;
        */

        return $stmtStart . $whereString . $stmtEnd;
    }

    /** Génére la déclaration SQL des spécialités médicales pour chaque docteur pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectDistinctForLaboOrdoDocsOnEvent(string $whereString): string
    {
        $stmtStart =
            'SELECT DISTINCT
                medic_event_list.laboOrdoDocID as docID,
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.laboOrdoDocID = doc_list.docID
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE';
        // ajouter medic_event_list.userID = 1 ou medic_event_list.medicEventID = 35

        $stmtEnd =
            'HAVING
                medic_event_list.laboOrdoDocID != 0
            ORDER BY
                medic_event_list.laboOrdoDocID,
                spe_medic_full_list.name
            ;';

        /*
            SELECT DISTINCT
                medic_event_list.laboOrdoDocID AS docID,
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.laboOrdoDocID = doc_list.docID
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE
                medic_event_list.userID = 1
            HAVING
                medic_event_list.laboOrdoDocID != 0
            ORDER BY
                medic_event_list.laboOrdoDocID,
                spe_medic_full_list.name
            ;
        */

        return $stmtStart . $whereString . $stmtEnd;
    }

    /** Génére la déclaration SQL des spécialités médicales pour chaque docteur pour les events voulus
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function stmtSelectDistinctForReplacedLaboOrdoDocsOnEvent(string $whereString): string
    {
        $stmtStart =
            'SELECT DISTINCT
                medic_event_list.laboOrdoReplacedDocDiagID AS docID,
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.laboOrdoReplacedDocDiagID = doc_list.docID
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE';
        // ajouter medic_event_list.userID = 1 ou medic_event_list.medicEventID = 35

        $stmtEnd = '
            HAVING
                medic_event_list.laboOrdoReplacedDocDiagID != 0
            ORDER BY
                medic_event_list.laboOrdoReplacedDocDiagID,
                spe_medic_full_list.name
            ;';

        /*
            SELECT DISTINCT
                medic_event_list.laboOrdoReplacedDocDiagID AS docID,
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name
            FROM
                medic_event_list
            INNER JOIN doc_list ON medic_event_list.laboOrdoReplacedDocDiagID = doc_list.docID
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE
                medic_event_list.userID = 1
            HAVING
                medic_event_list.laboOrdoReplacedDocDiagID != 0
            ORDER BY
                medic_event_list.laboOrdoReplacedDocDiagID,
                spe_medic_full_list.name
        */

        return $stmtStart . $whereString . $stmtEnd;
    }

    /** Génére la déclaration SQL listant toutes les spécialités médicales utilisées par le user
     * @param string $whereString   Liste des medicEventID au format medic_event_list.medicEventID = x
     * @return string               Déclaration SQL complète
     */
    public function selectSpeMedicUsedByUserStmt(string $whereString): string
    {
        $stmtStart =
            'SELECT DISTINCT
                spe_medic_full_list.speMedicID,
                spe_medic_full_list.name
            FROM
                doc_list
            INNER JOIN doc_spemedic_relation ON doc_list.docID = doc_spemedic_relation.docID
            INNER JOIN spe_medic_full_list ON doc_spemedic_relation.speMedicID = spe_medic_full_list.speMedicID
            WHERE';
        // ajouter doc_list.userID = 1

        $stmtEnd =
            ' ORDER BY spe_medic_full_list.name;';

        return $stmtStart . $whereString . $stmtEnd;
    }
}
