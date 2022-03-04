<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\lens;

/** Construction d'une ordonnance de lunettes
 */
class LensOrdoBuilder
{
    public function __destruct()
    {
    }

    /** Construction de la <div> de l'ordonnance de lentille
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string                   <div> de l'accordéon complet
     */
    public function ordoBuilder(array $singleOrdo): string
    {
        $lensOrdoHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/ordoAccordions/lens/lensOrdo.html');

        $lensOrdoHTML = str_replace('{ordoSightID}', $singleOrdo['ordoSightID'], $lensOrdoHTML);

        $lensOrdoHTML = str_replace('{frenchDate}', $singleOrdo['time']['frenchDate'], $lensOrdoHTML);

        $lensOrdoHTML = str_replace('{lensModel}', $singleOrdo['lens']['model'], $lensOrdoHTML);
        $lensOrdoHTML = str_replace('{lensDiameter}', $singleOrdo['lens']['diameter'], $lensOrdoHTML);
        $lensOrdoHTML = str_replace('{lensRadius}', $singleOrdo['lens']['radius'], $lensOrdoHTML);

        $lensOrdoHTML = str_replace('{rightEyeSentence}', $singleOrdo['rightEye']['sentence'], $lensOrdoHTML);
        $lensOrdoHTML = str_replace('{leftEyeSentence}', $singleOrdo['leftEye']['sentence'], $lensOrdoHTML);

        $lensOrdoHTML = str_replace('{ordoComment}', $singleOrdo['comment'], $lensOrdoHTML);

        return $lensOrdoHTML;
    }
}
