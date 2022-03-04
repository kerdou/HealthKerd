<?php

namespace HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventDocOffice;

class EventDocOfficeBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(string $docOfficeName): string
    {
        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventHeaderContent/sections/eventDocOffice.html');
        $templateHTML = str_replace('{docOfficeName}', $docOfficeName, $templateHTML);

        return $templateHTML;
    }
}
