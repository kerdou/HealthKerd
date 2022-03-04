<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\labo;

/** Construction d'une ordonnance de prélévement en laboratoire médical ainsi que ses prescriptions
 */
class LaboOrdoBuilder
{
    public function __destruct()
    {
    }

    /** Construction de la <div> de prélévement en laboratoire médical ainsi que la construction des prescriptions avec prescLaboObj->prescBuilder()
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string                   <div> de l'accordéon complet
     */
    public function ordoBuilder(array $singleOrdo)
    {
        $ordoHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/ordoAccordions/labo/laboOrdo.html');
        $ordoHTML = str_replace('{ordoLaboID}', $singleOrdo['ordoLaboID'], $ordoHTML);
        $ordoHTML = str_replace('{frenchDate}', $singleOrdo['time']['frenchDate'], $ordoHTML);
        $ordoHTML = str_replace('{ordoComment}', $singleOrdo['comment'], $ordoHTML);


        if (sizeof($singleOrdo['prescList']) > 0) {
            $prescTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/ordoAccordions/labo/laboPresc.html');
            $prescHTML = '';

            foreach ($singleOrdo['prescList'] as $presc) {
                $prescTemp = $prescTemplateHTML;
                $prescTemp = str_replace('{samplingType}', $presc['samplingType'], $prescTemp);
                $liHTML = '';

                if (sizeof($presc['elements']) > 0) {
                    foreach ($presc['elements'] as $element) {
                        $liTemp = '<li>' . $element['elementContent'] . '</li>';
                        $liHTML .= $liTemp;
                    }
                } else {
                    $liHTML = '<li>Cette prescription de contient aucun élément</li>';
                }

                $prescTemp = str_replace('{prescLiElements}', $liHTML, $prescTemp);
                $prescHTML .= $prescTemp;
            }

            $ordoHTML = str_replace('{prescElements}', $prescHTML, $ordoHTML);
        } else {
            $ordoHTML = str_replace('{prescElements}', '', $ordoHTML);
        }

        return $ordoHTML;
    }
}
