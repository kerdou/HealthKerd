<?php

namespace HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventReplacedDoc;

class EventReplacedDocBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $replacedDocData): string
    {
        $speMedicBadgeTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/docSpeMedic/docSpeMedic.html');
        $createdBadgesHTML = '';

        foreach ($replacedDocData['speMedicList'] as $key => $value) {
            $tempBadgeHTML = str_replace('{speMedicName}', $value['nameForDoc'], $speMedicBadgeTemplateHTML);
            $createdBadgesHTML .= $tempBadgeHTML;
        }

        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventHeaderContent/sections/eventReplacedDoc.html');
        $templateHTML = str_replace('{fullNameSentence}', $replacedDocData['fullNameSentence'], $templateHTML);
        $templateHTML = str_replace('{docSpeMedicBadges}', $createdBadgesHTML, $templateHTML);

        return $templateHTML;
    }
}
