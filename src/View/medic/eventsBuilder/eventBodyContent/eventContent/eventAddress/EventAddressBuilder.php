<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\eventAddress;

class EventAddressBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $docOfficeData): string
    {
        $templateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventAddressAccordion.html');
        $adressHTML = $this->eventFullAddrAccordContent($docOfficeData);
        $templateHTML = str_replace('{docOfficeAddress}', $adressHTML, $templateHTML);

        return $templateHTML;
    }

    /** Affichage de l'adresse complête du cabinet medical
     * @param array $addrData   Donnée de l'adresse
     * @return string           HTML des paragraphes de l'adresse complête
    */
    private function eventFullAddrAccordContent(array $addrData): string
    {
        $addressHTML = '';

        $addressHTML .= '<p class="mb-0">' . $addrData['name'] . '</p>';
        $addressHTML .= '<p class="mb-0">' . $addrData['addr1'] . '</p>';

        if (strlen($addrData['addr2']) > 0) {
            $addressHTML .= '<p class="mb-0">' . $addrData['addr2'] . '</p>';
        }

        $addressHTML .= '<p class="mb-0">' . $addrData['postCode'] . '</p>';
        $addressHTML .= '<p class="mb-0"> ' . $addrData['cityName'] . '</p>';

        return $addressHTML;
    }
}
