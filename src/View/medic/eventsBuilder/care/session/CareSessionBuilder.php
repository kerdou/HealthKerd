<?php

namespace HealthKerd\View\medic\eventsBuilder\care\session;

class CareSessionBuilder extends CareSessionBuilderFunctionsPool
{
    public function __destruct()
    {
    }

    /** */
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
        $careSessionArray['careElemBuilder'] = $this->careElemBuilder($sessionData);
        $careSessionArray['careElemULEnd'] = '</ul>'; // Pour cloturer l'UL des élements
        $careSessionArray['careComment'] = $this->careComment($sessionData, $medicEventID);
        $careSessionArray['careAccordionEnd'] = $this->careAccordionEnd();

        foreach ($careSessionArray as $sessionHTMLPortion) {
            $careSessionHTML .= $sessionHTMLPortion;
        }

        return $careSessionHTML;
    }
}
