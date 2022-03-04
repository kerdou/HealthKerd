<?php

namespace HealthKerd\View\medic\eventsBuilder\eventHeaderContent;

class EventHeaderContentBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $eventData): string
    {
        $headerTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventHeaderContent/eventHeaderTemplate.html');

        // eventCategory
        $eventCategoryBuilder = new \HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventCategory\EventCategoryBuilder();
        $eventCategoryContent = $eventCategoryBuilder->buildOrder($eventData['category']['catName']);
        $headerTemplateHTML = str_replace('{eventCategory}', $eventCategoryContent, $headerTemplateHTML);

        // eventThemes
        $eventThemesBuilder = new \HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventThemes\EventThemesBuilder();
        $eventThemesContent = $eventThemesBuilder->buildOrder($eventData['eventMedicThemes']);
        $headerTemplateHTML = str_replace('{eventThemes}', $eventThemesContent, $headerTemplateHTML);

        // eventAttendedDoc
        if ($eventData['doc']['attended']['docID'] != '0') {
            $eventAttendedDocBuilder = new \HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventAttendedDoc\EventAttendedDocBuilder();
            $eventAttendedDocContent = $eventAttendedDocBuilder->buildOrder($eventData['doc']['attended']);
            $headerTemplateHTML = str_replace('{eventAttendedDoc}', $eventAttendedDocContent, $headerTemplateHTML);
        } else {
            $headerTemplateHTML = str_replace('{eventAttendedDoc}', '', $headerTemplateHTML);
        }

        // eventReplacedDoc
        if ($eventData['doc']['replaced']['docID'] != '0') {
            $eventReplacedDocBuilder = new \HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventReplacedDoc\EventReplacedDocBuilder();
            $eventReplacedDocContent = $eventReplacedDocBuilder->buildOrder($eventData['doc']['replaced']);
            $headerTemplateHTML = str_replace('{eventReplacedDoc}', $eventReplacedDocContent, $headerTemplateHTML);
        } else {
            $headerTemplateHTML = str_replace('{eventReplacedDoc}', '', $headerTemplateHTML);
        }

        // eventSubject
        $eventSubjectBuilder = new \HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventSubject\EventSubjectBuilder();
        $eventSubjectContent = $eventSubjectBuilder->buildOrder($eventData['title']);
        $headerTemplateHTML = str_replace('{eventSubject}', $eventSubjectContent, $headerTemplateHTML);

        // eventDateTime
        $eventDateTimeBuilder = new \HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventDateTime\EventDateTimeBuilder();
        $eventDateTimeContent = $eventDateTimeBuilder->buildOrder($eventData['time']);
        $headerTemplateHTML = str_replace('{eventDateTime}', $eventDateTimeContent, $headerTemplateHTML);

        // eventDocOffice
        $eventDocOfficeBuilder = new \HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventDocOffice\EventDocOfficeBuilder();
        $eventDocOfficeContent = $eventDocOfficeBuilder->buildOrder($eventData['docOffice']['name']);
        $headerTemplateHTML = str_replace('{eventDocOffice}', $eventDocOfficeContent, $headerTemplateHTML);

        return $headerTemplateHTML;
    }
}
