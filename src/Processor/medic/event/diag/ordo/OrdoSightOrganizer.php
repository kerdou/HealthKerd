<?php

namespace HealthKerd\Processor\medic\event\diag\ordo;

/** Gestion des ordonnances optiques
 * * Séparation des ordonnances de lunettes et des ordonnances de lentilles
 */
class OrdoSightOrganizer
{
    private array $ordoSightList = array();

    /** Ordre de modification des ordonnances optiques
     * @param array $ordoSightList      Liste des ordonnances optiques
     * @return array                    Données modifiées
     */
    public function ordoSightGeneralBuildOrder(
        array $ordoSightList
    ) {
        $this->ordoSightList = $ordoSightList;

        $this->ordoSightContentOrganizer();
        $this->glassAndLensOrdoSentenceBuilder();

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
        // service de gestion du temps
        $dateAndTimeManagementBuilder = new \HealthKerd\Services\common\DateAndTimeManagement();
        $dateAndTimeProcessedData = $dateAndTimeManagementBuilder->dateAndTimeConverter(
            $value['date'],
            $_ENV['DATEANDTIME']['timezoneObj']
        );

        $tempArray['ordoSightID'] = $value['ordoSightID'];
        $tempArray['ordoType'] = 'glass';
        $tempArray['diagID'] = $value['diagID'];

        $tempArray['time']['date'] = $value['date'];
        $tempArray['time']['timestamp'] = $dateAndTimeProcessedData['timestamp'];
        $tempArray['time']['frenchDate'] = $dateAndTimeProcessedData['frenchDate'];

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
        // service de gestion du temps
        $dateAndTimeManagementBuilder = new \HealthKerd\Services\common\DateAndTimeManagement();
        $dateAndTimeProcessedData = $dateAndTimeManagementBuilder->dateAndTimeConverter(
            $value['date'],
            $_ENV['DATEANDTIME']['timezoneObj']
        );

        $tempArray['ordoSightID'] = $value['ordoSightID'];
        $tempArray['ordoType'] = 'lens';
        $tempArray['diagID'] = $value['diagID'];

        $tempArray['time']['date'] = $value['date'];
        $tempArray['time']['timestamp'] = $dateAndTimeProcessedData['timestamp'];
        $tempArray['time']['frenchDate'] = $dateAndTimeProcessedData['frenchDate'];

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
