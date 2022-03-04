<?php

namespace HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventDateTime;

class EventDateTimeBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $timeData): string
    {
        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventHeaderContent/sections/eventDateTime.html');
        $templateHTML = str_replace('{frenchDate}', $timeData['frenchDate'], $templateHTML);
        $templateHTML = str_replace('{time}', $timeData['time'], $templateHTML);

        return $templateHTML;
    }
}
