<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\eventComment;

class EventCommentBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(string $eventComment): string
    {
        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventComment.html');
        $templateHTML = str_replace('{eventComment}', $eventComment, $templateHTML);

        return $templateHTML;
    }
}
