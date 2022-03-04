<?php

namespace HealthKerd\View\medic\eventsBuilder\eventHeaderContent\eventCategory;

class EventCategoryBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(string $catName): string
    {
        $badgeTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/eventCategory/eventCategory.html');
        $badgeTemplateHTML = str_replace('{eventCategoryName}', $catName, $badgeTemplateHTML);

        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventHeaderContent/sections/eventCategory.html');
        $templateHTML = str_replace('{eventCategoryBadge}', $badgeTemplateHTML, $templateHTML);

        return $templateHTML;
    }
}
