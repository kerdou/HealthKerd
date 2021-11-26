<?php

namespace HealthKerd\View\medic\eventsBuilder;

class EventsBuilder extends EventsBuilderFunctionPool
{
    private array|null $eventsData = array();
    private array $allEventsHTMLArray = array();
    private string $allEventsFullHTML = '';

    private object $diagAccordionObj;

    public function __construct()
    {
        $this->diagAccordionObj = new \HealthKerd\View\medic\eventsBuilder\diag\DiagBuilder();
    }

    public function eventBuildOrder(array $eventsData)
    {
        $this->eventsData = $eventsData;

        foreach ($this->eventsData as $key => $value) {
            $eventFullHTML = '';
            $eventArray = array();

            $eventArray['eventAccordStart'] = $this->eventAccordStart($value);

            /** DEBUT DU HEADER DE L'EVENT */
            $eventArray['eventAccordHeaderStart'] = $this->eventAccordHeaderStart();

            $eventArray['attendedDocSection'] = '';
            if ($value['doc']['attended']['docID'] != 0) {
                $docSpeMedicBadges = $this->docSpeMedicBadges($value['doc']['attended']['speMedicList']);
                $eventArray['attendedDocSection'] = $this->attendedPro($value['doc']['attended'], $docSpeMedicBadges);
            }

            $eventArray['replacedDocSection'] = '';
            if ($value['doc']['substit']['docID'] != 0) {
                $docSpeMedicBadges = $this->docSpeMedicBadges($value['doc']['substit']['speMedicList']);
                $eventArray['replacedDocSection'] = $this->replacedPro($value['doc']['substit'], $docSpeMedicBadges);
            }

            $eventArray['eventDateTime'] = $this->eventDateTime($value['time']);
            $eventArray['eventDocOffice'] = $this->eventDocOffice($value['docOffice']);

            $eventArray['eventSubject'] = '';
            if (strlen($value['title']) > 0) {
                $eventArray['eventSubject'] = $this->eventSubject($value['title']);
            }

            $eventArray['eventCategory'] = $this->eventCategory($value['category']['catName']);
            $medicThemeBadges = $this->medicThemeBadges($value['eventMedicThemes']);
            $eventArray['eventMedicTheme'] = $this->eventMedicTheme($medicThemeBadges);
            $eventArray['eventContentButton'] = $this->eventContentButton($value);
            $eventArray['eventAccordHeaderEnd'] = $this->eventAccordHeaderEnd();
            /** FIN DU HEADER DE L'EVENT */

            /** DEBUT DU BODY DE L'EVENT */
            $eventArray['eventAccordBodyStart'] = $this->eventAccordBodyStart($value);
            $eventArray['eventFullAddrAccordStart'] = $this->eventFullAddrAccordStart($value);
            $eventArray['eventFullAddrAccordContent'] = $this->eventFullAddrAccordContent($value['docOffice']['data']);
            $eventArray['eventFullAddrAccordEnd'] = $this->eventFullAddrAccordEnd();
            $eventArray['eventAccordComment'] = $this->eventAccordComment($value);

            /** INSERER LE CONTENU DE L'EVENT ICI  */
            /** INSERER LE CONTENU DE L'EVENT ICI  */

            $eventArray['diagAccordion'] = '';
            if (sizeof($value['content']['diag']) > 0) {
                $eventArray['diagAccordion'] = $this->diagAccordionObj->diagBuilder($value);
            }

            /** INSERER LE CONTENU DE L'EVENT ICI  */
            /** INSERER LE CONTENU DE L'EVENT ICI  */

            $eventArray['eventAccordBodyEnd'] = $this->eventAccordBodyEnd();
            /** FIN DU BODY DE L'EVENT */

            $eventArray['eventAccordEnd'] = $this->eventAccordEnd();

            /** On colatione tous les morceaux de HTML */
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
