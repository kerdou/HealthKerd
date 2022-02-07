<?php

namespace HealthKerd\View\medic\eventsBuilder;

/** Construction des events soit passés, soit futurs
 * * Peut être lancé 2 fois pour les cas où le user veut afficher les events passés et à venir
 */
class EventsBuilder extends EventsBuilderFunctionPool
{
    private array $eventsData = array();
    private array $allEventsHTMLArray = array();
    private string $allEventsFullHTML = '';

    private object $diagAccordionObj;
    private object $careSessionAccordionObj;

    public function __construct()
    {
        $this->diagAccordionObj = new \HealthKerd\View\medic\eventsBuilder\diag\DiagBuilder();
        $this->careSessionAccordionObj = new \HealthKerd\View\medic\eventsBuilder\care\session\CareSessionBuilder();
    }

    public function __destruct()
    {
    }

    /** Construction de tous les éléments de l'accordéon d'affichage soit passés soit futurs
     * @param array $eventsData     Données de tous les événements soit passés et futurs
     * @return string               HTML de tous les events soit passés soit futurs
     */
    public function eventBuildOrder(array $eventsData)
    {
        $this->eventsData = $eventsData;

        // construction de chaque event
        foreach ($this->eventsData as $key => $value) {
            $eventFullHTML = '';
            $eventArray = array();

            $eventArray['eventAccordStart'] = $this->eventAccordStart($value['medicEventID']); // début de l'accordéon de l'event

            /** DEBUT DU HEADER DE L'EVENT */
            $eventArray['eventAccordHeaderStart'] = $this->eventAccordHeaderStart();

            $eventArray['eventCategory'] = $this->eventCategory($value['category']['catName']); // catégorie d'events

            $medicThemeBadges = $this->eventMedicThemeBadgesBuilder($value['eventMedicThemes']); // construction des badges des thèmes médicaux de l'event
            $eventArray['eventMedicTheme'] = $this->eventMedicTheme($medicThemeBadges);

            $eventArray['attendedDocSection'] = '';
            if ($value['doc']['attended']['docID'] != '0') {
                $docSpeMedicBadges = $this->docSpeMedicBadgesBuilder($value['doc']['attended']['speMedicList']);
                $eventArray['attendedDocSection'] = $this->attendedPro($value['doc']['attended']['fullNameSentence'], $docSpeMedicBadges);
            }

            $eventArray['replacedDocSection'] = '';
            if ($value['doc']['replaced']['docID'] != '0') {
                $docSpeMedicBadges = $this->docSpeMedicBadgesBuilder($value['doc']['replaced']['speMedicList']);
                $eventArray['replacedDocSection'] = $this->replacedPro($value['doc']['replaced']['fullNameSentence'], $docSpeMedicBadges);
            }

            $eventArray['eventSubject'] = '';
            if (strlen($value['title']) > 0) {
                $eventArray['eventSubject'] = $this->eventSubject($value['title']);
            }

            $eventArray['eventDateTime'] = $this->eventDateTime($value['time']);
            $eventArray['eventDocOffice'] = $this->eventDocOffice($value['docOffice']['name']);

            $eventArray['eventContentButton'] = $this->eventContentButton($value['medicEventID']);
            $eventArray['eventAccordHeaderEnd'] = $this->eventAccordHeaderEnd();
            /** FIN DU HEADER DE L'EVENT */

            /** DEBUT DU BODY DE L'EVENT */
            $eventArray['eventAccordBodyStart'] = $this->eventAccordBodyStart($value['medicEventID']);
            $eventArray['eventAccordComment'] = $this->eventAccordComment($value);

            $eventArray['eventFullAddrAccordStart'] = $this->eventFullAddrAccordStart($value['medicEventID']);
            $eventArray['eventFullAddrAccordContent'] = $this->eventFullAddrAccordContent($value['docOffice']);
            $eventArray['eventFullAddrAccordEnd'] = $this->eventFullAddrAccordEnd();

            /** INSERER LE CONTENU DE L'EVENT ICI  */
            /** INSERER LE CONTENU DE L'EVENT ICI  */

            $eventArray['diagAccordion'] = '';
            if (sizeof($value['content']['diag']) > 0) {
                $eventArray['diagAccordion'] = $this->diagAccordionObj->diagBuilder($value);
            }

            $eventArray['careSessionAccordion'] = '';
            if (sizeof($value['content']['careSession']) > 0) {
                $eventArray['careSessionAccordion'] = $this->careSessionAccordionObj->careSessionBuilder($value);
            }

            /** INSERER LE CONTENU DE L'EVENT ICI  */
            /** INSERER LE CONTENU DE L'EVENT ICI  */

            $eventArray['eventAccordBodyEnd'] = $this->eventAccordBodyEnd();
            /** FIN DU BODY DE L'EVENT */

            $eventArray['eventAccordEnd'] = $this->eventAccordEnd();

            /** On concatene tous les morceaux de HTML de l'event */
            foreach ($eventArray as $htmlPortion) {
                $eventFullHTML .= $htmlPortion;
            }

            /** On ajoute le contenu de tous les events dans $allEventsHTMLArray */
            array_push($this->allEventsHTMLArray, $eventFullHTML);
        }

        /** On accolle le contenu de tous les events dans $allEventsFullHTML */
        foreach ($this->allEventsHTMLArray as $singleEventHTML) {
            $this->allEventsFullHTML .= $singleEventHTML;
        }

        return $this->allEventsFullHTML;
    }
}
