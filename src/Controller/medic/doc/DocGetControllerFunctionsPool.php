<?php

namespace HealthKerd\Controller\medic\doc;

/** Depot de fonctions pour alléger le fichier DocGetController */
abstract class DocGetControllerFunctionsPool extends DocCommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }


    /** extraie les docID d'un Array
     * @param array $docList    Liste des données des docteurs
     * @return array            Liste des ID des docs
     */
    protected function docIDsExtractor(array $docList): array
    {
        $docIDList = array();

        foreach ($docList as $doc) {
            array_push($docIDList, $doc['docID']);
        }

        return $docIDList;
    }

    /** Récapitulatif des nombres et dates d'events passés et futurs
     */
    protected function eventsSummaryCreation(): void
    {
        $pastEventsQty = sizeof($this->pastEvents);
        $futureEventsQty = sizeof($this->futureEvents);

        $this->docList[0]['medicEvent']['qty']['past'] = $pastEventsQty;
        $this->docList[0]['medicEvent']['qty']['coming'] = $futureEventsQty;
        $this->docList[0]['medicEvent']['qty']['total'] = sizeof($this->medicEventList);


        if ($pastEventsQty > 0) {
            $this->docList[0]['medicEvent']['dates']['first'] = $this->pastEvents[0]['time']['frenchDate'];

            $this->docList[0]['medicEvent']['dates']['last'] = $this->pastEvents[$pastEventsQty - 1]['time']['frenchDate'];
        } else {
            $this->docList[0]['medicEvent']['dates']['first'] = 'Aucun';
            $this->docList[0]['medicEvent']['dates']['last'] = 'Aucun';
        }

        if ($futureEventsQty > 0) {
            $this->docList[0]['medicEvent']['dates']['next'] = $this->futureEvents[0]['time']['frenchDate'];
        } else {
            $this->docList[0]['medicEvent']['dates']['next'] = 'Aucun';
        }
    }

    /** convertie et ajoute la civilité du dr dans docList[$docKey]['title']
     * TODO à réviser
    */
    protected function docTitleAddition(): void
    {
        foreach ($this->docList as $docKey => $docValue) {
            switch ($docValue['title']) {
                case 'dr':
                    $this->docList[$docKey]['title'] = 'Dr ';
                    break;
                case 'mr':
                    $this->docList[$docKey]['title'] = 'Mr ';
                    break;
                case 'mrs':
                    $this->docList[$docKey]['title'] = 'Mme ';
                    break;
                case 'ms':
                    $this->docList[$docKey]['title'] = 'Mlle ';
                    break;
                case 'none':
                    $this->docList[$docKey]['title'] = '';
                    break;
            }
        }
    }

    /** Création de la string contenant le titre, le prénom et le nom du doc pour l'ajouter dans docList[$docKey]['fullNameSentence']
     * TODO à réviser
     * * Exemple: Dr Gregory House
     * * Le résultat varie suivant la présence ou l'absence du titre de docteur
     * * Le résultat varie suivant la présence ou l'absence du prénom
     */
    protected function docFullNameSentenceCreator(): void
    {
        foreach ($this->docList as $docKey => $docValue) {
            $titleType = '';
            $hasFirstName = false;

            // on vérifie le titre, seuls les cas des 'dr' et des 'none' sont vraiment ciblés
            if ($docValue['title'] == 'Dr ') {
                $titleType = 'dr';
            } elseif ($docValue['title'] == '') {
                $titleType = 'none';
            } else {
                $titleType = 'other';
            }

            // on vérifie la présence du prénom
            if (strlen($docValue['firstName']) > 0) {
                $hasFirstName = true;
            } else {
                $hasFirstName = false;
            }

            // les tests se déclenchent uniquement si ce n'est pas un docID == 0

            if ($titleType == 'dr' && $hasFirstName == true) {
                // si le title est 'dr' et qu'il y a un prénom alors 'fullNameSentence' = title + prénom + nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['title'] .
                ucwords($this->docList[$docKey]['firstName']) .
                ' ' .
                ucwords($this->docList[$docKey]['lastName']);
            } elseif ($titleType == 'dr' && $hasFirstName == false) {
                // si title est 'dr' mais qu'il n'y a pas de prénom alors 'fullNameSentence' = title + nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['title'] .
                ucwords($this->docList[$docKey]['lastName']);
            } elseif ($titleType == 'other' && $hasFirstName == true) {
                // si pas 'dr' mais présence de prénom alors 'fullNameSentence' = prénom + nom
                $this->docList[$docKey]['fullNameSentence'] =
                ucwords($this->docList[$docKey]['firstName']) .
                ' ' .
                ucwords($this->docList[$docKey]['lastName']);
            } elseif ($titleType == 'other' && $hasFirstName == false) {
                // si pas de 'dr' mais absence de prénom alors 'fullNameSentence' = title + nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['title'] .
                ucwords($this->docList[$docKey]['lastName']);
            } elseif ($titleType == 'none' && $hasFirstName == true) {
                // si le title est 'none' et qu'il y a un prénom alors 'fullNameSentence' = prénom + nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['firstName'] .
                ' ' .
                $this->docList[$docKey]['lastName'];
            } elseif ($titleType = 'none' && $hasFirstName == false) {
                // si le title est 'none' et qu'il n'y pas de prénom  alors 'fullNameSentence' = nom
                $this->docList[$docKey]['fullNameSentence'] =
                $this->docList[$docKey]['lastName'];
            }
        }
    }

    /** ajoute les spécialités médicales des docteurs
    */
    protected function speMedicAdditionToDocs(): void
    {
        foreach ($this->docList as $docKey => $docValue) {
            foreach ($this->speMedicList as $speKey => $speValue) {
                if ($docValue['docID'] == $speValue['docID']) {
                    array_push($this->docList[$docKey]['speMedicList'], $speValue);
                }
            }
        }
    }

    /** ajoute les informations du cabinet médical
    */
    protected function docOfficeAddition(): void
    {
        foreach ($this->docList as $docKey => $docValue) {
            foreach ($this->docOfficeList as $officeKey => $officeValue) {
                if ($docValue['docID'] == $officeValue['docID']) {
                    array_push($this->docList[$docKey]['docOfficeList'], $officeValue);
                }
            }
        }
    }

    /** Création et stockage de toutes les données temporelles d'aujourd'hui
     * TODO à réviser
    */
    protected function dateAndTimeCreator(): void
    {
        setlocale(LC_TIME, 'fr_FR', 'fra');
        $this->dataWorkbench['dateAndTime']['timezoneObj'] = timezone_open('Europe/Paris');
        $this->dataWorkbench['dateAndTime']['nowTimeObj'] = date_create('now', $this->dataWorkbench['dateAndTime']['timezoneObj']);

        // determine le début de la journée en tenant compte de la timezone
        $this->dataWorkbench['dateAndTime']['todayEarlyTimeObj'] = date_time_set($this->dataWorkbench['dateAndTime']['nowTimeObj'], 0, 0, 0, 0);
        $this->dataWorkbench['dateAndTime']['todayEarlyTimeOffset'] = date_offset_get($this->dataWorkbench['dateAndTime']['todayEarlyTimeObj']);
        $this->dataWorkbench['dateAndTime']['todayEarlyTimestamp'] = date_timestamp_get($this->dataWorkbench['dateAndTime']['todayEarlyTimeObj']) + $this->dataWorkbench['dateAndTime']['todayEarlyTimeOffset'];

        // determine le fin de la journée en tenant compte de la timezone
        $this->dataWorkbench['dateAndTime']['todayLateTimeObj'] = date_time_set($this->dataWorkbench['dateAndTime']['nowTimeObj'], 23, 59, 59, 999999);
        $this->dataWorkbench['dateAndTime']['todayLateTimeOffset'] = date_offset_get($this->dataWorkbench['dateAndTime']['todayLateTimeObj']);
        $this->dataWorkbench['dateAndTime']['todayLateTimestamp'] = date_timestamp_get($this->dataWorkbench['dateAndTime']['todayLateTimeObj'])  + $this->dataWorkbench['dateAndTime']['todayLateTimeOffset'];
    }

    /** Préparation de la partie ['time'] de l'event
     *  * medicEventList[$eventKey]['time']['dateTime'] sert à stocker l'objet dateTime qui sera utilisé pour la création de toutes les autres données temporelles
    */
    protected function medicEventArrayTimeModifier(): void
    {
        foreach ($this->medicEventList as $eventKey => $eventValue) {
            $this->medicEventList[$eventKey]['time']['dateTime'] = $eventValue['dateTime'];
            $this->medicEventList[$eventKey]['time']['timestamp'] = '';
            $this->medicEventList[$eventKey]['time']['frenchDate'] = '';
            $this->medicEventList[$eventKey]['time']['time'] = '';
        }
    }

    /** Ajout du timestamp, de la date complete en français et de l'heure
     * TODO à réviser
     */
    protected function timeManagement(): void
    {
        foreach ($this->medicEventList as $eventKey => $value) {
            $dateObj = date_create($value['time']['dateTime'], $this->dataWorkbench['dateAndTime']['timezoneObj']); // création de l'objet dateTime en tenant compte du décalage horaire correspondant à la date de l'event
            $UTCOffset = date_offset_get($dateObj); // récupération de l'offset de timezone
            $timestamp = date_timestamp_get($dateObj) + $UTCOffset; // on ajout l'écart de timezone au timestamp pour qu'il soit correct
            $this->medicEventList[$eventKey]['time']['timestamp'] = $timestamp;
            $this->medicEventList[$eventKey]['time']['frenchDate'] = utf8_encode(ucwords(gmstrftime('%A %e %B %Y', $timestamp))); // utf8_encode() pour s'assurer que les accents passent bien
            $this->medicEventList[$eventKey]['time']['time'] = gmstrftime('%H:%M', $timestamp);
        }
    }

    /** Tri des events dans 2 arrays par rapport à leur timestamp
     * TODO à réviser
    */
    protected function eventTimeDispatcher(): void
    {
        foreach ($this->medicEventList as $key => $value) {
            if ($value['time']['timestamp'] < $this->dataWorkbench['dateAndTime']['todayEarlyTimestamp']) {
                array_push($this->pastEvents, $value);
            } elseif ($value['time']['timestamp'] >= $this->dataWorkbench['dateAndTime']['todayEarlyTimestamp']) {
                array_push($this->futureEvents, $value);
            }
        }
    }

    /** Tri entre les events passés et futurs
     * TODO à réviser
     */
    protected function pastAndFutureEventsTimeSorting(): void
    {
        uasort($this->pastEvents, array($this, "incrTimestampEventSorting"));
        uasort($this->futureEvents, array($this, "incrTimestampEventSorting"));
    }


    /** Tri des events en ordre croissant par timestamp
     * TODO à réviser
    */
    protected function incrTimestampEventSorting($firstValue, $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] < $secondValue['time']['timestamp']) ? -1 : 1;
    }


    /** Tri des events en ordre décroissant par timestamp
     * TODO à réviser
    */
    protected function decrTimestampEventSorting($firstValue, $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] > $secondValue['time']['timestamp']) ? -1 : 1;
    }
}
