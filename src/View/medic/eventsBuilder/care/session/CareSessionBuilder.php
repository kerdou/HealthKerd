<?php

namespace HealthKerd\View\medic\eventsBuilder\care\session;

/** Construction de l'accordéon d'une session de soin
 */
class CareSessionBuilder extends CareSessionBuilderFunctionsPool
{
    public function __destruct()
    {
    }

    /** Construction des l'accordéon d'une session de soin
     * @param array $value      Données de la session de soin
     * @return string           HTML de l'accordéon de la session de soin
    */
    public function careSessionBuilder(array $value)
    {
        $careSessionHTML = '';
        $careSessionArray = array();

        $medicEventID = $value['medicEventID'];
        $sessionData = $value['content']['careSession'];


        //echo '<pre>';
        //print_r($value);
        //echo '</pre>';

        $careSessionArray['careAccordionStart'] = $this->careAccordionStart($sessionData, $medicEventID);
        $careSessionArray['careElemBuilder'] = $this->careElemBuilder($sessionData['elements']);
        $careSessionArray['careElemULEnd'] = '</ul>'; // Pour cloturer l'UL des élements
        $careSessionArray['careComment'] = $this->careComment($sessionData, $medicEventID);
        $careSessionArray['careAccordionEnd'] = $this->careAccordionEnd();

        foreach ($careSessionArray as $sessionHTMLPortion) {
            $careSessionHTML .= $sessionHTMLPortion;
        }

        return $careSessionHTML;
    }
}
