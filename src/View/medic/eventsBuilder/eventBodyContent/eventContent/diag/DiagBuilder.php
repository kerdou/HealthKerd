<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag;

/** Construction de l'accordéon de diagnostic et des sous-accordéons des contenus suivants:
 */
class DiagBuilder
{
    public function __destruct()
    {
    }

    /** Construction de l'accordéon de diagnostic et des sous-accordéons des contenus suivants:
     * @param array $value      Données du diagnostic
     * @return string           HTML de l'accordéon de diagnostic et de ses contenus
     */
    public function buildOrder(array $diagData): string
    {
        $diagTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/diagAccordionTemplate.html');

        // symptoms
        $symptomsLiElementsBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\symptomsLiElements\SymptomsLiElementsBuilder();
        $symptomsLiElementsContent = $symptomsLiElementsBuilder->buildOrder($diagData['symptoms']);
        $diagTemplateHTML = str_replace('{symptomsLiElements}', $symptomsLiElementsContent, $diagTemplateHTML);

        // checkpoints
        $checkpointsLiElementsBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\checkpointsLiElements\CheckpointsLiElementsBuilder();
        $checkpointsLiElementsContent = $checkpointsLiElementsBuilder->buildOrder($diagData['checkpoints']);
        $diagTemplateHTML = str_replace('{checkpointsLiElements}', $checkpointsLiElementsContent, $diagTemplateHTML);

        // conclusions
        $conclusionsUlAndLiElementsBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\conclusionsLiElements\ConclusionsLiElementsBuilder();
        $conclusionsUlAndLiElementsContent = $conclusionsUlAndLiElementsBuilder->buildOrder($diagData['conclusions']);
        $diagTemplateHTML = str_replace('{conclusionsUlAndLiElements}', $conclusionsUlAndLiElementsContent, $diagTemplateHTML);

        // comments
        $diagTemplateHTML = str_replace('{diagComments}', $diagData['comment'], $diagTemplateHTML);

        // ordo
        $ordoAccordionsBuilder = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\OrdoAccordionsBuilder();
        $ordoAccordionsContent = $ordoAccordionsBuilder->buildOrder($diagData['ordo']);
        $diagTemplateHTML = str_replace('{ordoAccordions}', $ordoAccordionsContent, $diagTemplateHTML);

        // diagID
        $diagTemplateHTML = str_replace('{diagID}', $diagData['diagID'], $diagTemplateHTML);

        return $diagTemplateHTML;
    }
}
