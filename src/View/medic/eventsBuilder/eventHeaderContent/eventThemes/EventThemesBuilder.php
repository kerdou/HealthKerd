<?php

namespace HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventThemes;

class EventThemesBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $eventMedicThemes)
    {
        $badgesTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/medicalThemes/eventMedicThemes.html');
        $createdBadgesHTML = '';

        foreach ($eventMedicThemes as $key => $value) {
            $tempBadgeHTML = str_replace('{themeName}', $value['name'], $badgesTemplateHTML);
            $createdBadgesHTML .= $tempBadgeHTML;
        }

        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventHeaderContent/sections/eventThemes.html');
        $templateHTML = str_replace('{eventMedicThemeBadges}', $createdBadgesHTML, $templateHTML);

        return $templateHTML;
    }
}
