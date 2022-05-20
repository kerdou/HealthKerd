<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent;

class EventBodyContentBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $eventData): string
    {
        $bodyTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/eventBodyTemplate.html');

        // commentaire de l'event
        $eventCommentBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\eventComment\EventCommentBuilder();
        $eventCommentContent = $eventCommentBuilder->buildOrder($eventData['comment']);
        $bodyTemplateHTML = str_replace('{eventComment}', $eventCommentContent, $bodyTemplateHTML);

        // accordéon d'adresse complete
        $eventAddressBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\eventAddress\EventAddressBuilder();
        $eventAddressContent = $eventAddressBuilder->buildOrder($eventData['docOffice']);
        $bodyTemplateHTML = str_replace('{eventAddressAccordion}', $eventAddressContent, $bodyTemplateHTML);

        // accordéon de diagnostic + ordonnances + prescriptions
        if (sizeof($eventData['content']['diag']) > 0) {
            $diagAccordionBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\DiagBuilder();
            $diagAccordionContent = $diagAccordionBuilder->buildOrder($eventData['content']['diag']);
        } else {
            $diagAccordionContent = '';
        }
        $bodyTemplateHTML = str_replace('{diagAccordion}', $diagAccordionContent, $bodyTemplateHTML);

        // accordéon de session de soin
        if (sizeof($eventData['content']['careSession']) > 0) {
            $careSessionAccordionBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\care\session\CareSessionBuilder();
            $careSessionAccordionContent = $careSessionAccordionBuilder->buildOrder($eventData['content']['careSession']);
        } else {
            $careSessionAccordionContent = '';
        }
        $bodyTemplateHTML = str_replace('{careSessionAccordion}', $careSessionAccordionContent, $bodyTemplateHTML);

        // medicEventID
        $bodyTemplateHTML = str_replace('{medicEventID}', $eventData['medicEventID'], $bodyTemplateHTML);

        return $bodyTemplateHTML;
    }
}
