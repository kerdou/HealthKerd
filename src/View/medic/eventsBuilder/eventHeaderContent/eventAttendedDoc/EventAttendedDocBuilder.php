<?php

namespace HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventAttendedDoc;

class EventAttendedDocBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $attendedDocData): string
    {
        $speMedicBadgeTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/docSpeMedic/docSpeMedic.html');
        $createdBadgesHTML = '';

        foreach ($attendedDocData['speMedicList'] as $key => $value) {
            $tempBadgeHTML = str_replace('{speMedicName}', $value['nameForDoc'], $speMedicBadgeTemplateHTML);
            $createdBadgesHTML .= $tempBadgeHTML;
        }

        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventHeaderContent/sections/eventAttendedDoc.html');
        $templateHTML = str_replace('{fullNameSentence}', $attendedDocData['fullNameSentence'], $templateHTML);
        $templateHTML = str_replace('{docSpeMedicBadges}', $createdBadgesHTML, $templateHTML);

        return $templateHTML;
    }
}
