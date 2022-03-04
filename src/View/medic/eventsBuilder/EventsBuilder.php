<?php

namespace HealthKerd\View\medic\eventsBuilder;

/** Construction des events soit passés, soit futurs
 * * Peut être lancé 2 fois pour les cas où le user veut afficher les events passés et à venir
 */
class EventsBuilder
{
    private array $eventsData = array();
    private array $allEventsHTMLArray = array();
    private string $allEventsFullHTML = '';

    public function __destruct()
    {
    }

    /** Construction de tous les éléments de l'accordéon d'affichage soit passés soit futurs
     * @param array $eventsData     Données de tous les événements soit passés et futurs
     * @return string               HTML de tous les events soit passés soit futurs
     */
    public function eventBuildOrder(array $eventsData): string
    {
        $this->eventsData = $eventsData;

        // construction de chaque event
        foreach ($this->eventsData as $key => $value) {
            $eventFullHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/globalEventAccordionTemplate.html');

            // contenu du header de l'accordéon de l'event
            $headerBuilder = new \HealthKerd\View\medic\eventsBuilder\eventHeaderContent\EventHeaderContentBuilder();
            $headerContent = $headerBuilder->buildOrder($value);
            $eventFullHTML = str_replace('{eventHeaderContent}', $headerContent, $eventFullHTML);

            // contenu du body de l'accordéon de l'event
            $bodyBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\EventBodyContentBuilder();
            $bodyContent = $bodyBuilder->buildOrder($value);
            $eventFullHTML = str_replace('{eventBodyContent}', $bodyContent, $eventFullHTML);

            $eventFullHTML = str_replace('{medicEventID}', $value['medicEventID'], $eventFullHTML);
            array_push($this->allEventsHTMLArray, $eventFullHTML);
        }

        /** On accolle le contenu de tous les events dans $allEventsFullHTML */
        foreach ($this->allEventsHTMLArray as $singleEventHTML) {
            $this->allEventsFullHTML .= $singleEventHTML;
        }

        return $this->allEventsFullHTML;
    }
}
