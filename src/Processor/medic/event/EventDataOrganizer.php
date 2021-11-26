<?php

namespace HealthKerd\Processor\medic\event;

class EventDataOrganizer
{
    private array|null $eventArray = array();
    private array|null $dateAndTime = array();
    private array|null $docOfficeList = array();
    private array|null $medicEventThemesRelation = array();
    private array|null $medicEventSpemedicRelation = array();
    private array|null $docSpemedicRelation = array();
    private array|null $speMedicFullList = array();
    private array|null $docList = array();

    /**
     *
     */
    public function eventGeneralBuildOrder(
        array|null $eventArray,
        array|null $dateAndTime,
        array|null $docOfficeList,
        array|null $medicEventThemesRelation,
        array|null $medicEventSpemedicRelation,
        array|null $docSpemedicRelation,
        array|null $speMedicFullList,
        array|null $docList
    ) {
        $this->eventArray = $eventArray;
        $this->dateAndTime = $dateAndTime;
        $this->docOfficeList = $docOfficeList;
        $this->medicEventThemesRelation = $medicEventThemesRelation;
        $this->medicEventSpemedicRelation = $medicEventSpemedicRelation;
        $this->docSpemedicRelation = $docSpemedicRelation;
        $this->speMedicFullList = $speMedicFullList;
        $this->docList = $docList;

        $this->contentOrganizer();
        $this->docTitleAndFullNameAddition();
        $this->docSpeMedicAddition();
        $this->timeManagement();
        $this->eventThemesAddition();
        $this->eventSpeMedicAddition();
        $this->docOfficeDataAdder();

        //echo '<pre>';
        //print_r($this->eventArray);
        //echo '</pre>';

        return $this->eventArray;
    }

    /**
     *
     */
    private function contentOrganizer()
    {
        $localEventArray = array();

        foreach ($this->eventArray as $value) {
            //echo '<pre>';
            //print_r($value);
            //echo '</pre>';

            $tempArray = array();

            $tempArray['medicEventID'] = $value['medicEventID'];
            $tempArray['title'] = $value['title'];
            $tempArray['isWithPro'] = $value['isWithPro'];
            $tempArray['comment'] = $value['comment'];

            $tempArray['time']['dateTime'] = $value['dateTime'];
            $tempArray['time']['timestamp'] = '';
            $tempArray['time']['frenchDate'] = '';
            $tempArray['time']['time'] = '';

            $tempArray['eventMedicThemes'] = array();
            $tempArray['eventSpeMedic'] = array();

            $tempArray['user']['userID'] = $value['userID'];
            $tempArray['user']['firstName'] = $value['userFirstName'];
            $tempArray['user']['lastName'] = $value['userLastName'];

            $tempArray['category']['catID'] = $value['medicEventCatID'];
            $tempArray['category']['catName'] = $value['eventCatName'];

            $tempArray['doc']['attended']['docID'] = $value['docID'];
            $tempArray['doc']['attended']['title'] = '';
            $tempArray['doc']['attended']['firstName'] = $value['docFirstName'];
            $tempArray['doc']['attended']['lastName'] = $value['docLastName'];
            $tempArray['doc']['attended']['fullNameSentence'] = '';
            $tempArray['doc']['attended']['speMedicIdList'] = array();
            $tempArray['doc']['attended']['speMedicList'] = array();
            $tempArray['doc']['attended']['booleans']['isMyMainDoc'] = 0;
            $tempArray['doc']['attended']['booleans']['canVisitHome'] = 0;
            $tempArray['doc']['attended']['booleans']['isRetired'] = 0;
            $tempArray['doc']['attended']['booleans']['isBlacklisted'] = 0;

            $tempArray['doc']['substit']['docID'] = $value['replacedDocID'];
            $tempArray['doc']['substit']['title'] = '';
            $tempArray['doc']['substit']['firstName'] = $value['substitDocFirstName'];
            $tempArray['doc']['substit']['lastName'] = $value['substitDocLastName'];
            $tempArray['doc']['substit']['fullNameSentence'] = '';
            $tempArray['doc']['substit']['speMedicIdList'] = array();
            $tempArray['doc']['substit']['speMedicList'] = array();
            $tempArray['doc']['substit']['booleans']['isMyMainDoc'] = 0;
            $tempArray['doc']['substit']['booleans']['canVisitHome'] = 0;
            $tempArray['doc']['substit']['booleans']['isRetired'] = 0;
            $tempArray['doc']['substit']['booleans']['isBlacklisted'] = 0;

            $tempArray['doc']['laboOrdoAttended']['docID'] = $value['laboOrdoDocID'];
            $tempArray['doc']['laboOrdoAttended']['title'] = '';
            $tempArray['doc']['laboOrdoAttended']['firstName'] = $value['laboOrdoDocFirstName'];
            $tempArray['doc']['laboOrdoAttended']['lastName'] = $value['laboOrdoDocLastName'];
            $tempArray['doc']['laboOrdoAttended']['fullNameSentence'] = '';
            $tempArray['doc']['laboOrdoAttended']['speMedicIdList'] = array();
            $tempArray['doc']['laboOrdoAttended']['speMedicList'] = array();
            $tempArray['doc']['laboOrdoAttended']['booleans']['isMyMainDoc'] = 0;
            $tempArray['doc']['laboOrdoAttended']['booleans']['canVisitHome'] = 0;
            $tempArray['doc']['laboOrdoAttended']['booleans']['isRetired'] = 0;
            $tempArray['doc']['laboOrdoAttended']['booleans']['isBlacklisted'] = 0;

            $tempArray['doc']['laboOrdoSubstit']['docID'] = $value['laboOrdoReplacedDocDiagID'];
            $tempArray['doc']['laboOrdoSubstit']['title'] = '';
            $tempArray['doc']['laboOrdoSubstit']['firstName'] = $value['substitLaboOrdoDocFirstName'];
            $tempArray['doc']['laboOrdoSubstit']['lastName'] = $value['substitLaboOrdoDocLastName'];
            $tempArray['doc']['laboOrdoSubstit']['fullNameSentence'] = '';
            $tempArray['doc']['laboOrdoSubstit']['speMedicIdList'] = array();
            $tempArray['doc']['laboOrdoSubstit']['speMedicList'] = array();
            $tempArray['doc']['laboOrdoSubstit']['booleans']['isMyMainDoc'] = 0;
            $tempArray['doc']['laboOrdoSubstit']['booleans']['canVisitHome'] = 0;
            $tempArray['doc']['laboOrdoSubstit']['booleans']['isRetired'] = 0;
            $tempArray['doc']['laboOrdoSubstit']['booleans']['isBlacklisted'] = 0;

            $tempArray['docOffice']['docOfficeID'] = $value['docOfficeID'];
            $tempArray['docOffice']['name'] = $value['docOfficeName'];
            $tempArray['docOffice']['locationType'] = $value['locationType'];
            $tempArray['docOffice']['data'] = array();

            $tempArray['content']['diag'] = array();

            $tempArray['content']['treatPharmaSession'] = array();
            $tempArray['content']['treatPharmaUsageMod'] = array();

            $tempArray['content']['careSession'] = array();
            $tempArray['content']['careUsageMod'] = array();

            $tempArray['content']['laboNoOrdoSampling'] = array();
            $tempArray['content']['laboOrdoSampling'] = array();

            $tempArray['content']['vaxSession'] = array();
            $tempArray['content']['vaxUsageMod'] = array();

            $tempArray['contentType']['containsDiag'] = $value['containsDiag'];
            $tempArray['contentType']['containsOrdoPharma'] = $value['containsOrdoPharma'];
            $tempArray['contentType']['containsOrdoLabo'] = $value['containsOrdoLabo'];
            $tempArray['contentType']['containsOrdoVax'] = $value['containsOrdoVax'];
            $tempArray['contentType']['containsOrdoSight'] = $value['containsOrdoSight'];
            $tempArray['contentType']['containsLaboSampling'] = $value['containsLaboSampling'];
            $tempArray['contentType']['containsLaboAwaitingSlot'] = $value['containsLaboAwaitingSlot'];

            //echo '<pre>';
            //print_r($tempArray);
            //echo '</pre>';

            array_push($localEventArray, $tempArray);
        }

        $this->eventArray = $localEventArray;
    }


    /**
     *
     */
    private function docTitleAndFullNameAddition()
    {
        $docPresenceTypes = ['attended', 'substit', 'laboOrdoAttended', 'laboOrdoSubstit'];

        foreach ($docPresenceTypes as $value) {
            $this->docTitleAddition($value);
            $this->docFullNameSentenceCreator($value);
        }
    }


    /**
     *
     */
    private function docTitleAddition(string $docPresence)
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($this->docList as $docKey => $docValue) {
                if ($eventValue['doc'][$docPresence]['docID'] == $docValue['docID']) {
                    switch ($docValue['title']) {
                        case 'dr':
                            $this->eventArray[$eventKey]['doc'][$docPresence]['title'] = 'Dr ';
                            break;
                        case 'mr':
                            $this->eventArray[$eventKey]['doc'][$docPresence]['title'] = 'Mr ';
                            break;
                        case 'mrs':
                            $this->eventArray[$eventKey]['doc'][$docPresence]['title'] = 'Mme ';
                            break;
                        case 'ms':
                            $this->eventArray[$eventKey]['doc'][$docPresence]['title'] = 'Mlle ';
                            break;
                        case 'none':
                            $this->eventArray[$eventKey]['doc'][$docPresence]['title'] = '';
                            break;
                    }
                }
            }
        }
    }


    /**
     *
     */
    private function docFullNameSentenceCreator(string $docPresence)
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            $docIdIsOK = false;
            $titleType = '';
            $hasFirstName = false;

            // on vérifie qu'il ne s'agisse pas d'un slot de doc vide
            if ($eventValue['doc'][$docPresence]['docID'] != '0') {
                $docIdIsOK = true;
            }

            // on vérifie le titre, seuls les cas des 'dr' et des 'none' sont vraiment ciblés
            if ($eventValue['doc'][$docPresence]['title'] == 'Dr ') {
                $titleType = 'dr';
            } elseif ($eventValue['doc'][$docPresence]['title'] == '') {
                $titleType = 'none';
            } else {
                $titleType = 'other';
            }

            // on vérifie la présence du prénom
            if (strlen($eventValue['doc'][$docPresence]['firstName']) > 0) {
                $hasFirstName = true;
            } else {
                $hasFirstName = false;
            }

            // les tests se déclenchent uniquement si ce n'est pas un docID == 0
            if ($docIdIsOK == true) {
                if ($titleType == 'dr' && $hasFirstName == true) {
                    // si le title est 'dr' et qu'il y a un prénom alors 'fullNameSentence' = title + prénom + nom
                    $this->eventArray[$eventKey]['doc'][$docPresence]['fullNameSentence'] =
                    $this->eventArray[$eventKey]['doc'][$docPresence]['title'] .
                    ucwords($this->eventArray[$eventKey]['doc'][$docPresence]['firstName']) .
                    ' ' .
                    ucwords($this->eventArray[$eventKey]['doc'][$docPresence]['lastName']);
                } elseif ($titleType == 'dr' && $hasFirstName == false) {
                    // si title est 'dr' mais qu'il n'y a pas de prénom alors 'fullNameSentence' = title + nom
                    $this->eventArray[$eventKey]['doc'][$docPresence]['fullNameSentence'] =
                    $this->eventArray[$eventKey]['doc'][$docPresence]['title'] .
                    ucwords($this->eventArray[$eventKey]['doc'][$docPresence]['lastName']);
                } elseif ($titleType == 'other' && $hasFirstName == true) {
                    // si pas 'dr' mais présence de prénom alors 'fullNameSentence' = prénom + nom
                    $this->eventArray[$eventKey]['doc'][$docPresence]['fullNameSentence'] =
                    ucwords($this->eventArray[$eventKey]['doc'][$docPresence]['firstName']) .
                    ' ' .
                    ucwords($this->eventArray[$eventKey]['doc'][$docPresence]['lastName']);
                } elseif ($titleType == 'other' && $hasFirstName == false) {
                    // si pas de 'dr' mais absence de prénom alors 'fullNameSentence' = title + nom
                    $this->eventArray[$eventKey]['doc'][$docPresence]['fullNameSentence'] =
                    $this->eventArray[$eventKey]['doc'][$docPresence]['title'] .
                    ucwords($this->eventArray[$eventKey]['doc'][$docPresence]['lastName']);
                } elseif ($titleType == 'none' && $hasFirstName == true) {
                    // si le title est 'none' et qu'il y a un prénom alors 'fullNameSentence' = prénom + nom
                    $this->eventArray[$eventKey]['doc'][$docPresence]['fullNameSentence'] =
                    $this->eventArray[$eventKey]['doc'][$docPresence]['firstName'] .
                    ' ' .
                    $this->eventArray[$eventKey]['doc'][$docPresence]['lastName'];
                } elseif ($titleType = 'none' && $hasFirstName == false) {
                    // si le title est 'none' et qu'il n'y pas de prénom  alors 'fullNameSentence' = nom
                    $this->eventArray[$eventKey]['doc'][$docPresence]['fullNameSentence'] =
                    $this->eventArray[$eventKey]['doc'][$docPresence]['lastName'];
                }
            }
        }
    }


    /**
     *
     */
    private function docSpeMedicAddition()
    {
        $docPresenceTypes = ['attended', 'substit', 'laboOrdoAttended', 'laboOrdoSubstit'];

        foreach ($docPresenceTypes as $value) {
            $this->docSpeMedicIdAdder($value);
            $this->docSpeMedicAdder($value);
        }
    }


    /**
     *
     */
    private function docSpeMedicIdAdder(string $docPresence)
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($this->docSpemedicRelation as $speKey => $speValue) {
                if ($eventValue['doc'][$docPresence]['docID'] == $speValue['docID']) {
                    array_push(
                        $this->eventArray[$eventKey]['doc'][$docPresence]['speMedicIdList'],
                        $speValue['speMedicID']
                    );
                }
            }
        }
    }

    /**
     *
     */
    private function docSpeMedicAdder(string $docPresence)
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($eventValue['doc'][$docPresence]['speMedicIdList'] as $docSpeMedicKey => $docSpeMedicValue) {
                foreach ($this->speMedicFullList as $speKey => $speValue) {
                    if ($docSpeMedicValue == $speValue['speMedicID']) {
                        array_push(
                            $this->eventArray[$eventKey]['doc'][$docPresence]['speMedicList'],
                            $speValue
                        );
                    }
                }
            }
        }
    }

    /**
     *
     */
    private function timeManagement()
    {
        foreach ($this->eventArray as $key => $value) {
            $dateObj = date_create($value['time']['dateTime'], $this->dateAndTime['timezoneObj']);
            $UTCOffset = date_offset_get($dateObj); // récupération de l'offset de timezone
            $timestamp = date_timestamp_get($dateObj) + $UTCOffset; // on ajout l'écart de timezone au timestamp pour qu'il soit correct
            $this->eventArray[$key]['time']['timestamp'] = $timestamp;
            $this->eventArray[$key]['time']['frenchDate'] = utf8_encode(ucwords(gmstrftime('%A %e %B %Y', $timestamp))); // utf8_encode() pour s'assurer que les accents passent bien
            $this->eventArray[$key]['time']['time'] = gmstrftime('%H:%M', $timestamp);
        }
    }

    /**
     *
     */
    private function eventThemesAddition()
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($this->medicEventThemesRelation as $themeKey => $themeValue) {
                if ($eventValue['medicEventID'] == $themeValue['medicEventID']) {
                    array_push($this->eventArray[$eventKey]['eventMedicThemes'], $themeValue);
                }
            }
        }
    }

    /**
     *
     */
    private function eventSpeMedicAddition()
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($this->medicEventSpemedicRelation as $speKey => $speValue) {
                if ($eventValue['medicEventID'] == $speValue['medicEventID']) {
                    array_push($this->eventArray[$eventKey]['eventSpeMedic'], $speValue);
                }
            }
        }
    }

    /**
     *
     */
    private function docOfficeDataAdder()
    {
        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($this->docOfficeList as $officeKey => $officeValue) {
                if ($eventValue['docOffice']['docOfficeID'] == $officeValue['docOfficeID']) {
                    $this->eventArray[$eventKey]['docOffice']['data'] = $officeValue;
                }
            }
        }
    }
}
