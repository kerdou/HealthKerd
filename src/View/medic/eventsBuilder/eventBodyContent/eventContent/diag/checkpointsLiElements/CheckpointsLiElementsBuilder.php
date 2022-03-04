<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\checkpointsLiElements;

class CheckpointsLiElementsBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $checkpointsData): string
    {
        $checkpointsHTML = '';

        if (sizeof($checkpointsData) > 0) {
            $checkpointTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/liElements/checkpoints/checkpointTemplate.html');

            foreach ($checkpointsData as $value) {
                $checkpointTemplateHTML = str_replace('{checkpoint}', $value['checkpoint'], $checkpointTemplateHTML);
                $checkpointsHTML .= $checkpointTemplateHTML;
            }
        } else {
            $checkpointsHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/liElements/checkpoints/noCheckpointTemplate.html');
        }

        return $checkpointsHTML;
    }
}
