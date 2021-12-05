<?php

namespace HealthKerd\View\medic\eventsBuilder;

class EventsBuilder extends EventsBuilderFunctionPool
{
    private array|null $eventsData = array();
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

    public function eventBuildOrder(array $eventsData)
    {
        $this->eventsData = $eventsData;

        foreach ($this->eventsData as $key => $value) {
            $eventFullHTML = '';
            $eventArray = array();

            $eventArray['eventAccordStart'] = $this->eventAccordStart($value);

            /** DEBUT DU HEADER DE L'EVENT */
            $eventArray['eventAccordHeaderStart'] = $this->eventAccordHeaderStart();

            $eventArray['eventCategory'] = $this->eventCategory($value['category']['catName']);
            $medicThemeBadges = $this->medicThemeBadges($value['eventMedicThemes']);
            $eventArray['eventMedicTheme'] = $this->eventMedicTheme($medicThemeBadges);

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

            $eventArray['eventSubject'] = '';
            if (strlen($value['title']) > 0) {
                $eventArray['eventSubject'] = $this->eventSubject($value['title']);
            }

            $eventArray['eventDateTime'] = $this->eventDateTime($value['time']);
            $eventArray['eventDocOffice'] = $this->eventDocOffice($value['docOffice']);

            $eventArray['eventContentButton'] = $this->eventContentButton($value);
            $eventArray['eventAccordHeaderEnd'] = $this->eventAccordHeaderEnd();
            /** FIN DU HEADER DE L'EVENT */

            /** DEBUT DU BODY DE L'EVENT */
            $eventArray['eventAccordBodyStart'] = $this->eventAccordBodyStart($value);
            $eventArray['eventAccordComment'] = $this->eventAccordComment($value);

            $eventArray['eventFullAddrAccordStart'] = $this->eventFullAddrAccordStart($value);
            $eventArray['eventFullAddrAccordContent'] = $this->eventFullAddrAccordContent($value['docOffice']['data']);
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

            /** On concatene tous les morceaux de HTML */
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
