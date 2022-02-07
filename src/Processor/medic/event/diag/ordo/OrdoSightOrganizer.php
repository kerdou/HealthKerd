<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

/** Gestion des ordonnances optiques
 * * Séparation des ordonnances de lunettes et des ordonnances de lentilles
 */
class OrdoSightOrganizer
{

    private array|null $ordoSightList = array();
    private array|null $dateAndTime = array();

    /** Ordre de modification des ordonnances optiques
     * @param array $ordoSightList      Liste des ordonnances optiques
     * @param array $dateAndTime        Informations de temps utilisées pour dater les ordonnances
     * @return array                    Données modifiées
     */
    public function ordoSightGeneralBuildOrder(
        array|null $ordoSightList,
        array|null $dateAndTime
    ) {
        $this->ordoSightList = $ordoSightList;
        $this->dateAndTime = $dateAndTime;

        $this->ordoSightContentOrganizer();
        $this->timeManagement();
        $this->glassAndLensOrdoSentenceBuilder();

        //echo '<pre>';
        //    print_r($this->ordoSightList);
        //echo '</pre>';

        return $this->ordoSightList;
    }

    /** Tri des ordonnances de lunettes et de lentilles
     * avant de les réorganiser puis de les renvoyer dans $ordoSightList
     */
    private function ordoSightContentOrganizer()
    {
        $ordoTempArray = array();

        foreach ($this->ordoSightList as $value) {
            $tempArray = array();

            switch ($value['ordoType']) {
                case 'glass':
                    $tempArray = $this->glassOrganizer($value);
                    break;

                case 'lens':
                    $tempArray = $this->lensOrganizer($value);
                    break;
            }

            array_push($ordoTempArray, $tempArray);
        }

        $this->ordoSightList = $ordoTempArray;
    }

    /** Réorganisation des ordonnances de lunettes
     * @param array $value  Données d'une ordonnance avant réorganisation
     * @return array        Données réorganisées
     */
    private function glassOrganizer(array $value)
    {
        //var_dump($value);
        $tempArray = array();

        $tempArray['ordoSightID'] = $value['ordoSightID'];
        $tempArray['ordoType'] = 'glass';
        $tempArray['diagID'] = $value['diagID'];

        $tempArray['time']['date'] = $value['date'];
        $tempArray['time']['timestamp'] = '';
        $tempArray['time']['frenchDate'] = '';

        $tempArray['comment'] = $value['comment'];

        $tempArray['pupDist'] = $value['glassPupDist'];

        $tempArray['rightEye']['sphereSign'] = $value['rightSphereSign'];
        $tempArray['rightEye']['sphereValue'] = $value['rightSphereValue'];
        $tempArray['rightEye']['cylinderSign'] = $value['rightCylinderSign'];
        $tempArray['rightEye']['cylinderValue'] = $value['rightCylinderValue'];
        $tempArray['rightEye']['axis'] =  $value['rightAxis'];
        $tempArray['rightEye']['addValue'] = $value['glassRightAddValue'];
        $tempArray['rightEye']['sentence'] = '';

        $tempArray['leftEye']['sphereSign'] = $value['leftSphereSign'];
        $tempArray['leftEye']['sphereValue'] = $value['leftSphereValue'];
        $tempArray['leftEye']['cylinderSign'] = $value['leftCylinderSign'];
        $tempArray['leftEye']['cylinderValue'] = $value['leftCylinderValue'];
        $tempArray['leftEye']['axis'] =  $value['leftAxis'];
        $tempArray['leftEye']['addValue']  = $value['glassLeftAddValue'];
        $tempArray['leftEye']['sentence'] = '';

        $tempArray['idStorage']['userID'] = $value['userID'];
        $tempArray['idStorage']['medicEventID'] = $value['medicEventID'];
        $tempArray['idStorage']['docID'] = $value['docID'];
        $tempArray['idStorage']['replacedDocID'] = $value['replacedDocID'];

        return $tempArray;
    }

    /** Réorganisation des ordonnances de lentilles
     * @param array $value  Données d'une ordonnance avant réorganisation
     * @return array        Données réorganisées
     */
    private function lensOrganizer(array $value)
    {
        //var_dump($value);
        $tempArray = array();

        $tempArray['ordoSightID'] = $value['ordoSightID'];
        $tempArray['ordoType'] = 'lens';
        $tempArray['diagID'] = $value['diagID'];

        $tempArray['time']['date'] = $value['date'];
        $tempArray['time']['timestamp'] = '';
        $tempArray['time']['frenchDate'] = '';

        $tempArray['comment'] = $value['comment'];

        $tempArray['lens']['model'] = $value['lensModel'];
        $tempArray['lens']['diameter'] = $value['lensDiameter'];
        $tempArray['lens']['radius'] = $value['lensRadius'];

        $tempArray['rightEye']['sphereSign'] = $value['rightSphereSign'];
        $tempArray['rightEye']['sphereValue'] = $value['rightSphereValue'];
        $tempArray['rightEye']['cylinderSign'] = $value['rightCylinderSign'];
        $tempArray['rightEye']['cylinderValue'] = $value['rightCylinderValue'];
        $tempArray['rightEye']['axis'] =  $value['rightAxis'];
        $tempArray['rightEye']['sentence'] = '';

        $tempArray['leftEye']['sphereSign'] = $value['leftSphereSign'];
        $tempArray['leftEye']['sphereValue'] = $value['leftSphereValue'];
        $tempArray['leftEye']['cylinderSign'] = $value['leftCylinderSign'];
        $tempArray['leftEye']['cylinderValue'] = $value['leftCylinderValue'];
        $tempArray['leftEye']['axis'] =  $value['leftAxis'];
        $tempArray['leftEye']['sentence'] = '';

        $tempArray['idStorage']['userID'] = $value['userID'];
        $tempArray['idStorage']['medicEventID'] = $value['medicEventID'];
        $tempArray['idStorage']['docID'] = $value['docID'];
        $tempArray['idStorage']['replacedDocID'] = $value['replacedDocID'];

        return $tempArray;
    }

    /** Ajout de timestamp et de date compléte écrite en français
     */
    private function timeManagement()
    {
        foreach ($this->ordoSightList as $key => $value) {
            $dateObj = date_create($value['time']['date'], $this->dateAndTime['timezoneObj']);
            $UTCOffset = date_offset_get($dateObj); // récupération de l'offset de timezone
            $timestamp = date_timestamp_get($dateObj) + $UTCOffset; // on ajout l'écart de timezone au timestamp pour qu'il soit correct
            $this->ordoSightList[$key]['time']['timestamp'] = $timestamp;
            $this->ordoSightList[$key]['time']['frenchDate'] = utf8_encode(ucwords(gmstrftime('%A %e %B %Y', $timestamp))); // utf8_encode() pour s'assurer que les accents passent bien
        }
    }

    /** Ajout des phrases pour les ordonnances d'oeil gauche et droite de lunettes et photos
     */
    private function glassAndLensOrdoSentenceBuilder()
    {
        foreach ($this->ordoSightList as $key => $value) {
            $this->ordoSightList[$key]['rightEye']['sentence'] = $this->perEyeSentenceBuilder($value['rightEye']);
            $this->ordoSightList[$key]['leftEye']['sentence'] = $this->perEyeSentenceBuilder($value['leftEye']);
        }
    }

    /** Conversion des datas en phrases pour les ordonnances d'oeil gauche et droite de lunettes et photos
     * @param array $eyeArray   Données pour la correction d'un seul oeil
     * @return string           Phrase regroupant tous les paramétres de la correction dans le même format que sur l'ordonnance
     */
    private function perEyeSentenceBuilder(array $eyeArray)
    {
        $sphereSign = $eyeArray['sphereSign'];
        $sphereValue = $this->roundNumberExtender($eyeArray['sphereValue']);
        $cylinderSign = $eyeArray['cylinderSign'];
        $cylinderValue = $this->roundNumberExtender($eyeArray['cylinderValue']);
        $axis = $eyeArray['axis'];

        $stringBase =   $sphereSign . '' . $sphereValue . ' (' . $cylinderSign . '' . $cylinderValue . ') ' . $axis . '°';

        $addStringExtension = '';

        if (array_key_exists('addValue', $eyeArray)) {
            $addValue = $this->roundNumberExtender($eyeArray['addValue']);

            if ($addValue != '0.00') {
                $addStringExtension = ' Add ' . $addValue;
            }
        }

        return $stringBase . $addStringExtension;
    }

    /** Modification de l'affichage de certaines valeurs pour répliquer le format des ordonnances
     * Ajout de .00 pour les chiffres entiers et '0' aux chiffres qui n'ont qu'une décimale
     * @param string $string    Chiffre à modifier
     * @return string           Chiffre modifié
     */
    private function roundNumberExtender(string $string)
    {
        $pointQty = substr_count($string, '.');

        if ($pointQty == 1) {
            $explodedString = explode('.', $string);

            if (strlen($explodedString[1]) == 1) {
                $string = $string . '0';
            }
        } else {
            $string = $string . '.00';
        }

        return $string;
    }
}
