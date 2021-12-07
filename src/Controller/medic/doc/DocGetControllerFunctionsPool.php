<?php

namespace HealthKerd\Controller\medic\doc;

/** Controleur de la section 'accueil' */
abstract class DocGetControllerFunctionsPool extends DocCommonController
{
    /** */
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }


    /** */
    protected function docIDsExtractor(array $docList)
    {
        $docIDList = array();

        foreach ($docList as $doc) {
            array_push($docIDList, $doc['docID']);
        }

        return $docIDList;
    }


    /**
     *
     */
    protected function showEventsWithOneDoc(string $docID)
    {
        date_default_timezone_set('Europe/Paris');
        $medicEventIdFinder = new \HealthKerd\Model\medic\eventIdFinder\EventIdFinder();
        $medicEventDataGatherer = new \HealthKerd\Model\medic\eventDataGatherer\EventDataGatherer();
        $medicEventArrayBuildOrder = new \HealthKerd\Processor\medic\MedicArrayBuildOrder();
        $this->docView = new \HealthKerd\View\medic\doc\eventsWithOneDoc\EventsWithOneDocPageBuilder();

        $medicEventsIdResult = array();
        $medicEventsIdResult = $medicEventIdFinder->eventsIdsFromOneDocId($docID);

        // conversion des ID d'event en integer
        $medicEventsIdList = array();
        foreach ($medicEventsIdResult as $value) {
            array_push($medicEventsIdList, intval($value['medicEventID']));
        }

        $medicEvtOriginalDataStore = $medicEventDataGatherer->eventIdReceiver($medicEventsIdList);
        $medicEvtProcessedDataStore = $medicEventArrayBuildOrder->eventDataReceiver($medicEvtOriginalDataStore);

        //echo '<pre>';
        //print_r($medicEvtOriginalDataStore);
        //echo '</pre>';

        // vidage de $medicEvtOriginalDataStore
        unset($medicEvtOriginalDataStore);
        $medicEvtOriginalDataStore = array();

        $this->docView->dataReceiver($medicEvtProcessedDataStore);
    }


    /**
     *
     */
    protected function docTitleAddition()
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


    /**
     *
     */
    protected function docFullNameSentenceCreator()
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


    /** */
    protected function speMedicAdditionToDocs()
    {
        foreach ($this->docList as $docKey => $docValue) {
            foreach ($this->speMedicList as $speKey => $speValue) {
                if ($docValue['docID'] == $speValue['docID']) {
                    array_push($this->docList[$docKey]['speMedicList'], $speValue);
                }
            }
        }
    }


    /** */
    protected function docOfficeAddition()
    {
        foreach ($this->docList as $docKey => $docValue) {
            foreach ($this->docOfficeList as $officeKey => $officeValue) {
                if ($docValue['docID'] == $officeValue['docID']) {
                    array_push($this->docList[$docKey]['docOfficeList'], $officeValue);
                }
            }
        }
    }

    /** */
    protected function speMedicBadgeListBuildUp()
    {
        $speMedicIDList = array();

        foreach ($this->speMedicList as $key => $value) {
            array_push($speMedicIDList, $value['speMedicID']);
        }

        $speMedicIDList = array_unique($speMedicIDList);
        sort($speMedicIDList, SORT_NUMERIC); // impératif pour que les index s'incrémentent de +1 à chaque élement, sinon le foreach utilisant array_key_exists() ne peut pas marcher

        $speMedicBadgeList = array();

        // s'il y a un match entre le speMedicID et que cette spé n'a pas déjà été ajoutée à $speMedicBadgeList, on l'ajoute
        foreach ($speMedicIDList as $idKey => $idValue) {
            foreach ($this->speMedicList as $speKey => $speValue) {
                if (($idValue == $speValue['speMedicID']) && (array_key_exists($idKey, $speMedicBadgeList) == false)) {
                    $tempArray = [
                        'speMedicID' => $speValue['speMedicID'],
                        'speMedicName' => $speValue['name']
                    ];
                    array_push($speMedicBadgeList, $tempArray);
                }
            }
        }

        //var_dump($speMedicBadgeList);
        return $speMedicBadgeList;
    }



    /** Création et stockage de toutes les données temporelles
     * @param array $dateAndTime Stocke toutes les données temporelles
     */
    protected function dateAndTimeCreator()
    {
        setlocale(LC_TIME, 'fr_FR', 'fra');
        $this->dataWorkbench['dateAndTime']['timezoneObj'] = timezone_open('Europe/Paris');
        $this->dataWorkbench['dateAndTime']['nowTimeObj'] = date_create('now', $this->dataWorkbench['dateAndTime']['timezoneObj']);

        $this->dataWorkbench['dateAndTime']['todayEarlyTimeObj'] = date_time_set($this->dataWorkbench['dateAndTime']['nowTimeObj'], 0, 0, 0, 0);
        $this->dataWorkbench['dateAndTime']['todayEarlyTimeOffset'] = date_offset_get($this->dataWorkbench['dateAndTime']['todayEarlyTimeObj']);
        $this->dataWorkbench['dateAndTime']['todayEarlyTimestamp'] = date_timestamp_get($this->dataWorkbench['dateAndTime']['todayEarlyTimeObj']) + $this->dataWorkbench['dateAndTime']['todayEarlyTimeOffset'];

        $this->dataWorkbench['dateAndTime']['todayLateTimeObj'] = date_time_set($this->dataWorkbench['dateAndTime']['nowTimeObj'], 23, 59, 59, 999999);
        $this->dataWorkbench['dateAndTime']['todayLateTimeOffset'] = date_offset_get($this->dataWorkbench['dateAndTime']['todayLateTimeObj']);
        $this->dataWorkbench['dateAndTime']['todayLateTimestamp'] = date_timestamp_get($this->dataWorkbench['dateAndTime']['todayLateTimeObj'])  + $this->dataWorkbench['dateAndTime']['todayLateTimeOffset'];
    }



    protected function medicEventArrayTimeModifier()
    {
        foreach ($this->medicEventList as $eventKey => $eventValue) {
            $this->medicEventList[$eventKey]['time']['dateTime'] = $eventValue['dateTime'];
            $this->medicEventList[$eventKey]['time']['timestamp'] = '';
            $this->medicEventList[$eventKey]['time']['frenchDate'] = '';
            $this->medicEventList[$eventKey]['time']['time'] = '';
        }
    }



    /**
     *
     */
    protected function timeManagement()
    {
        foreach ($this->medicEventList as $eventKey => $value) {
            $dateObj = date_create($value['time']['dateTime'], $this->dataWorkbench['dateAndTime']['timezoneObj']);
            $UTCOffset = date_offset_get($dateObj); // récupération de l'offset de timezone
            $timestamp = date_timestamp_get($dateObj) + $UTCOffset; // on ajout l'écart de timezone au timestamp pour qu'il soit correct
            $this->medicEventList[$eventKey]['time']['timestamp'] = $timestamp;
            $this->medicEventList[$eventKey]['time']['frenchDate'] = utf8_encode(ucwords(gmstrftime('%A %e %B %Y', $timestamp))); // utf8_encode() pour s'assurer que les accents passent bien
            $this->medicEventList[$eventKey]['time']['time'] = gmstrftime('%H:%M', $timestamp);
        }
    }



    /** Tri des events dans 2 arrays par rapport à leur timestamp */
    protected function eventTimeDispatcher()
    {
        foreach ($this->medicEventList as $key => $value) {
            if ($value['time']['timestamp'] < $this->dataWorkbench['dateAndTime']['todayEarlyTimestamp']) {
                array_push($this->pastEvents, $value);
            } elseif ($value['time']['timestamp'] >= $this->dataWorkbench['dateAndTime']['todayEarlyTimestamp']) {
                array_push($this->futureEvents, $value);
            }
        }
    }


    /**
     *
     */
    protected function pastAndFutureEventsTimeSorting()
    {
        uasort($this->pastEvents, array($this, "incrTimestampEventSorting"));
        uasort($this->futureEvents, array($this, "incrTimestampEventSorting"));
    }


    /** Tri des events en ordre croissant par timestamp */
    protected function incrTimestampEventSorting($firstValue, $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] < $secondValue['time']['timestamp']) ? -1 : 1;
    }


    /** Tri des events en ordre décroissant par timestamp */
    protected function decrTimestampEventSorting($firstValue, $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] > $secondValue['time']['timestamp']) ? -1 : 1;
    }


    protected function eventsSummaryCreation()
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
}
