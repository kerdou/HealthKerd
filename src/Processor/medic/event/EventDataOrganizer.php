<?php

namespace HealthKerd\Processor\medic\event;

/** Réorganisation et remplissage des données à l'échelon 'event' et pas les contenus */
class EventDataOrganizer
{
    private array $eventArray = array();
    private array $dateAndTime = array();
    private array $medicEventThemesRelation = array();
    private array $medicEventSpemedicRelation = array();
    private array $docList = array();

    public function __destruct()
    {
    }

    /** Ordre de réagencement et remplissage des données à l'échelon 'event' et pas les contenus
     * @param array $eventArray                     Liste des events
     * @param array $medicEventThemesRelation       Liste des thèmes médicaux de l'event
     * @param array $medicEventSpemedicRelation     Liste des spécialités médicales de l'event
     * @param array $docList                        Données des docteurs
     * @param array $dateAndTime                    Informations de temps de la journée en cours
     * @return array                                Contenu réorgnisé et remplit
     */
    public function eventGeneralBuildOrder(
        array $eventArray,
        array $medicEventThemesRelation,
        array $medicEventSpemedicRelation,
        array $docList,
        array $dateAndTime
    ) {
        $this->eventArray = $eventArray;
        $this->medicEventThemesRelation = $medicEventThemesRelation;
        $this->medicEventSpemedicRelation = $medicEventSpemedicRelation;
        $this->docList = $docList;
        $this->dateAndTime = $dateAndTime;

        $this->contentOrganizer();
        $this->timeManagement();
        $this->eventThemesAddition();
        $this->eventSpeMedicAddition();
        $this->docManagement();

        //echo '<pre>';
        //    var_dump($this->docList);
        //    print_r($this->speMedicFullList);
        //    print_r($this->eventArray);
        //echo '</pre>';

        return $this->eventArray;
    }

    /** Réagencement des données des events pour plus facilement retrouver les données
     * * général
     * * time
     * * medic theme
     * * event spe medic
     * * doc
     * * docOffice
     * * content
     * * contentType
     */
    private function contentOrganizer()
    {
        $localEventArray = array();

        foreach ($this->eventArray as $value) {
            //echo '<pre>';
            //  print_r($value);
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

            $tempArray['category']['catID'] = $value['medicEventCatID'];
            $tempArray['category']['catName'] = $value['eventCatName'];

            $tempArray['doc']['attended']['docID'] = $value['docID'];
            $tempArray['doc']['replaced']['docID'] = $value['replacedDocID'];
            $tempArray['doc']['laboOrdoAttended']['docID'] = $value['laboOrdoDocID'];
            $tempArray['doc']['replacedLaboOrdo']['docID'] = $value['laboOrdoReplacedDocDiagID'];

            $tempArray['docOffice']['docOfficeID'] = $value['docOfficeID'];
            $tempArray['docOffice']['locationType'] = $value['locationType'];
            $tempArray['docOffice']['name'] = $value['docOfficeName'];
            $tempArray['docOffice']['addr1'] = $value['docOfficeAddr1'];
            $tempArray['docOffice']['addr2'] = $value['docOfficeAddr2'];
            $tempArray['docOffice']['postCode'] = $value['docOfficePostCode'];
            $tempArray['docOffice']['cityName'] = $value['docOfficeCityName'];

            $tempArray['content']['diag'] = array();

            $tempArray['content']['treatPharmaSession'] = array();
            $tempArray['content']['treatPharmaUsageMod'] = array();

            $tempArray['content']['careSession'] = array();
            $tempArray['content']['careUsageMod'] = array();

            $tempArray['content']['laboNoOrdoSamplingSession'] = array();
            $tempArray['content']['laboOrdoSamplingSession'] = array();

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

    /** Gestion du temps pour chaque event et envoie dans infos dans $eventArray[$key]['time'][]
     * * Création de timestamp
     * * Création de date sous forme de phrase française. Ex: Lundi 28 Janvier 2022
     * * Création d'heure au format HH:MM
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

    /** Ajout des thèmes médicaux des events dans $eventArray[$eventKey]['eventMedicThemes'][]
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

    /** Ajout des spécialités médicales des events dans $eventArray[$eventKey]['eventSpeMedic'][]
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

    /** Gestion des titres et spécialités médicales des docteurs dans les 4 types de présence possibles
     * * attended: Docteur présent durant la session
     * * replaced: Docteur remplacé durant la session
     * * laboOrdoAttended: Docteur ayant prescrit une ordonnance de prélèvement médical ou une vaccination
     * * replacedLaboOrdo: Docteur remplacé pendant la prescription d'une ordonnance de prélèvement médical ou une vaccination
     */
    private function docManagement()
    {
        $docPresenceTypes = ['attended', 'replaced', 'laboOrdoAttended', 'replacedLaboOrdo'];

        foreach ($this->eventArray as $eventKey => $eventValue) {
            foreach ($docPresenceTypes as $presValue) {
                if (strlen($eventValue['doc'][$presValue]['docID']) > 0) {
                    $data = $this->docDataAdder($eventValue['doc'][$presValue]['docID']);

                    // ajout des données uniquement si une correspondance est trouvée dans docDataAdder()
                    if ($data != null) {
                        $this->eventArray[$eventKey]['doc'][$presValue] = $data;
                    }
                }
            }
        }
    }

    /** Recherche des données de docteur pour la présence de docteur actuellement traitée
     * @param string $docID     ID du docteur actuellement traité
     * @return array            Données du docteur correspondant
     */
    private function docDataAdder(string $docID)
    {
        foreach ($this->docList as $docValue) {
            if ($docID == $docValue['docID']) {
                return $docValue;
            }
        }
    }
}
