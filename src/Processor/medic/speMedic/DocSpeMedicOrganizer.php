<?php

namespace HealthKerd\Processor\medic\speMedic;

/** Classe de modification des données des docteurs
 */
class DocSpeMedicOrganizer
{
    public function __destruct()
    {
    }

    /** Fusion sans doublons des données de specialités médicales des docs
     * @param array $attendedDocSpe             Spé medicales des docteurs présents lors les events
     * @param array $replacedDocSpe             Spé medicales des docteurs remplacées lors des events
     * @param array $laboOrdoDocSpe             Spé medicales des docteurs ayant fait une ordonnance de prélèvement en labo médical
     * @param array $laboOrdoReplacedDocSpe     Spé medicales des docteurs remplacés lors de la prescription d'une ordonnance de prélèvement en labo médical
     * @return array                            Renvoie les données modifiées
     */
    public function docSpeMedicOrganizer(
        array $attendedDocSpe,
        array $replacedDocSpe,
        array $laboOrdoDocSpe,
        array $laboOrdoReplacedDocSpe
    ) {
        $speArray['mergedSpe'] = [
            ...$attendedDocSpe,
            ...$replacedDocSpe,
            ...$laboOrdoDocSpe,
            ...$laboOrdoReplacedDocSpe
        ];

        $speArray['docandSpeIdList'] = array();
        $speArray['uniqueSpeList'] = array();

        // filtre les spé médicales pour qu'elles soient uniques et rassemblés dans $speArray['uniqueSpeList']
        foreach ($speArray['mergedSpe'] as $key => $value) {
            $idCombi = $value['docID'] . '-' . $value['speMedicID'];

            if (in_array($idCombi, $speArray['docandSpeIdList']) == false) {
                array_push($speArray['docandSpeIdList'], $idCombi);
                array_push($speArray['uniqueSpeList'], $value);
            }
        }

        //echo '<pre>';
        //    print_r($speArray['docandSpeIdList']);
        //    print_r($speArray['uniqueSpeList']);
        //echo '</pre>';

        return $speArray['uniqueSpeList'];
    }
}
