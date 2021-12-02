<?php

namespace HealthKerd\View\medic\eventsBuilder\ordo\vax;

class VaxOrdoBuilder extends VaxOrdoBuilderFunctionsPool
{
    private object $prescVaxObj;

    /** */
    public function __construct()
    {
        $this->prescVaxObj = new \HealthKerd\View\medic\eventsBuilder\presc\vax\PrescVaxBuilder();
    }

    /** */
    public function ordoBuilder(array $singleOrdo, string $medicEventID)
    {
        //echo '<pre>';
        //print_r($singleOrdo);
        //echo '</pre>';

        $ordoHTML = '';
        $ordoArray = array();

        $ordoArray['ordoVaxStarter'] = $this->ordoVaxStarter($singleOrdo, $medicEventID);

        /** INSERT PRESC HERE */
        /** INSERT PRESC HERE */

        $ordoArray['prescVax'] = '';

        if (sizeof($singleOrdo['prescList']) > 0) {
            $prescArray = array();

            foreach ($singleOrdo['prescList'] as $presc) {
                $prescHTML = $this->prescVaxObj->prescBuilder($presc);
                array_push($prescArray, $prescHTML);
            }

            foreach ($prescArray as $prescHTMLPortion) {
                $ordoArray['prescVax'] .= $prescHTMLPortion;
            }
        }

        /** INSERT PRESC HERE */
        /** INSERT PRESC HERE */

        $ordoArray['ordoComment'] = $this->ordoComment($singleOrdo, $medicEventID);
        $ordoArray['ordoVaxEnd'] = $this->ordoVaxEnd();

        foreach ($ordoArray as $ordoHTMLPortion) {
            $ordoHTML .= $ordoHTMLPortion;
        }

        return $ordoHTML;
    }
}
