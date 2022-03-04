<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\care\session;

/** Construction de l'accordéon d'une session de soin
 */
class CareSessionBuilder
{
    public function __destruct()
    {
    }

    /** Construction des l'accordéon d'une session de soin
     * @param array $careSessionData      Données de la session de soin
     * @return string           HTML de l'accordéon de la session de soin
    */
    public function buildOrder(array $careSessionData): string
    {
        $careSessionHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/care/careSessionAccordion/careSessionAccordionTemplate.html');
        $careSessionHTML = str_replace('{sessionComment}', $careSessionData['comment'], $careSessionHTML);

        if (sizeof($careSessionData['elements']) > 0) {
            $elementLiHTML = '';

            foreach ($careSessionData['elements'] as $element) {
                $liTemp = '<li>' . $element['name'] . '</li>';
                $elementLiHTML .= $liTemp;
            }
        } else {
            $elementLiHTML = '<li>Aucun élément signalé</li>';
        }
        $careSessionHTML = str_replace('{careLiElements}', $elementLiHTML, $careSessionHTML);

        $careSessionHTML = str_replace('{careSessionID}', $careSessionData['careSessionID'], $careSessionHTML);

        return $careSessionHTML;
    }
}
