<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\symptomsLiElements;

class SymptomsLiElementsBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $symptomsData): string
    {
        $symptomsHTML = '';

        if (sizeof($symptomsData) > 0) {
            $symptomTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/liElements/symptoms/symptomTemplate.html');

            foreach ($symptomsData as $value) {
                $symptomTemplateHTML = str_replace('{symptom}', $value['symptom'], $symptomTemplateHTML);
                $symptomsHTML .= $symptomTemplateHTML;
            }
        } else {
            $symptomsHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/liElements/symptoms/noSymptomTemplate.html');
        }

        return $symptomsHTML;
    }
}
