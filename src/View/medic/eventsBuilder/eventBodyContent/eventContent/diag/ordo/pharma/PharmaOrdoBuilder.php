<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\pharma;

/** Construction d'une ordonnance pharmacologique
 */
class PharmaOrdoBuilder
{
    public function __destruct()
    {
    }

    /** Construction de la <div> de l'ordonnance pharmacologique
     * * Construction des prescription avec prescBuilder()
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string                   <div> de l'accordéon complet
     */
    public function ordoBuilder(array $singleOrdo)
    {
        $ordoHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/ordoAccordions/treatPharma/pharmaOrdo.html');
        $ordoHTML = str_replace('{ordoPharmaID}', $singleOrdo['ordoPharmaID'], $ordoHTML);
        $ordoHTML = str_replace('{frenchDate}', $singleOrdo['time']['frenchDate'], $ordoHTML);


        if (sizeof($singleOrdo['prescList']) > 0) {
            $prescTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/ordoAccordions/treatPharma/pharmaPresc.html');
            $treatNameBadgeTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/pharmaTreat/treatPharmaName.html');
            $roleOnAffectBadgeTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/treatRoleOnAffect/treatPharmaRoleOnAffect.html');
            $affectNameBadgeTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/medicAffect/medicAffect.html');

            foreach ($singleOrdo['prescList'] as $presc) {
                $prescTemp = $prescTemplateHTML;
                $prescTemp = str_replace('{prescContent}', $presc['content'], $prescTemp);

                $treatNameTemp = $treatNameBadgeTemplate;
                $treatNameTemp = str_replace('{treatPharmaName}', $presc['treatPharmaName'], $treatNameTemp);
                $prescTemp = str_replace('{treatPharmaNameBadge}', $treatNameTemp, $prescTemp);

                $roleTemp = $roleOnAffectBadgeTemplate;
                $roleTemp = str_replace('{treatPharmaRoleOnAffect}', $presc['roleName'], $roleTemp);
                $prescTemp = str_replace('{treatPharmaRoleOnAffectBadge}', $roleTemp, $prescTemp);

                $affectNameTemp = $affectNameBadgeTemplate;
                $affectNameTemp = str_replace('{medicAffectName}', $presc['affectName'], $affectNameTemp);
                $prescTemp = str_replace('{medicAffectNameBadge}', $affectNameTemp, $prescTemp);

                $ordoHTML = str_replace('{pharmaPrescElements}', $prescTemp, $ordoHTML);
            }
        } else {
            $ordoHTML = str_replace('{pharmaPrescElements}', '', $ordoHTML);
        }

        return $ordoHTML;
    }
}
