<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\glass;

/** Construction d'une ordonnance de lunettes
 */
class GlassOrdoBuilder
{
    public function __destruct()
    {
    }

    /** Construction de la <div> de l'ordonnance de lunettes
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string                   <div> de l'accordéon complet
     */
    public function ordoBuilder(array $singleOrdo): string
    {
        $glassOrdoHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/ordoAccordions/glass/glassOrdo.html');

        $glassOrdoHTML = str_replace('{ordoSightID}', $singleOrdo['ordoSightID'], $glassOrdoHTML);

        $glassOrdoHTML = str_replace('{frenchDate}', $singleOrdo['time']['frenchDate'], $glassOrdoHTML);

        $glassOrdoHTML = str_replace('{pupDist}', $singleOrdo['pupDist'], $glassOrdoHTML);

        $glassOrdoHTML = str_replace('{rightEyeSentence}', $singleOrdo['rightEye']['sentence'], $glassOrdoHTML);
        $glassOrdoHTML = str_replace('{leftEyeSentence}', $singleOrdo['leftEye']['sentence'], $glassOrdoHTML);

        $glassOrdoHTML = str_replace('{ordoComment}', $singleOrdo['comment'], $glassOrdoHTML);

        return $glassOrdoHTML;
    }
}
