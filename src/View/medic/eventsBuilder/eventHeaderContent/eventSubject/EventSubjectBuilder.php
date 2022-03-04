<?php

namespace HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventSubject;

class EventSubjectBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(string $subject): string
    {
        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventHeaderContent/sections/eventSubject.html');
        $templateHTML = str_replace('{eventSubject}', $subject, $templateHTML);

        return $templateHTML;
    }
}
