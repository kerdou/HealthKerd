<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\vax;

/** Construction d'une ordonnance vaccinale
 */
class VaxOrdoBuilder
{
    public function __destruct()
    {
    }

    /** Construction de la <div> de l'ordonnance vaccinale ainsi que ses prescriptions avec prescBuilder()
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string                   <div> de l'accordéon complet
     */
    public function ordoBuilder(array $singleOrdo)
    {
        $ordoHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/ordoAccordions/vax/vaxOrdo.html');
        $ordoHTML = str_replace('{ordoVaxID}', $singleOrdo['ordoVaxID'], $ordoHTML);
        $ordoHTML = str_replace('{frenchDate}', $singleOrdo['time']['frenchDate'], $ordoHTML);
        $ordoHTML = str_replace('{ordoComment}', $singleOrdo['comment'], $ordoHTML);

        if (sizeof($singleOrdo['prescList']) > 0) {
            $prescTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/ordoAccordions/vax/vaxPresc.html');
            $treatBadgeHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/pharmaTreat/treatPharmaName.html');
            $prescHTML = '';

            foreach ($singleOrdo['prescList'] as $presc) {
                $treatBadgeTemp = $treatBadgeHTML;
                $treatBadgeTemp = str_replace('{treatPharmaName}', $presc['name'], $treatBadgeTemp);

                $prescTemp = $prescTemplateHTML;
                $prescTemp = str_replace('{treatPharmaBadge}', $treatBadgeTemp, $prescTemp);
                $prescTemp = str_replace('{prescContent}', $presc['comment'], $prescTemp);

                $prescHTML .= $prescTemp;
            }

            $ordoHTML = str_replace('{vaxPrescElements}', $prescHTML, $ordoHTML);
        } else {
            $ordoHTML = str_replace('{vaxPrescElements}', '', $ordoHTML);
        }

        return $ordoHTML;
    }
}
