<?php

namespace HealthKerd\View\medic\eventsBuilder\ordo\pharma;

class PharmaOrdoBuilder extends PharmaOrdoBuilderFunctionsPool
{
    private object $pharmaPrescObj;

    /** */
    public function __construct()
    {
        $this->pharmaPrescObj = new \HealthKerd\View\medic\eventsBuilder\presc\pharma\PharmaPrescBuilder();
    }

    public function __destruct()
    {
    }

    /** */
    public function ordoBuilder(array $singleOrdo, string $medicEventID)
    {
        //echo '<pre>';
        //print_r($singleOrdo);
        //echo '</pre>';

        $ordoHTML = '';
        $ordoArray = array();

        $ordoArray['ordoPharmaStarter'] = $this->ordoPharmaStarter($singleOrdo, $medicEventID);


        $ordoArray['pharmaPresc'] = '';
        if (sizeof($singleOrdo['prescList']) > 0) {
            $prescArray = array();

            foreach ($singleOrdo['prescList'] as $presc) {
                $prescHTML = $this->pharmaPrescObj->prescBuilder($presc, $medicEventID);
                array_push($prescArray, $prescHTML);
            }

            foreach ($prescArray as $prescHTMLPortion) {
                $ordoArray['pharmaPresc'] .= $prescHTMLPortion;
            }
        }

        $ordoArray['ordoPharmaComment'] = $this->ordoPharmaComment($singleOrdo, $medicEventID);
        $ordoArray['ordoPharmaEnd'] = $this->ordoPharmaEnd();


        foreach ($ordoArray as $ordoHTMLPortion) {
            $ordoHTML .= $ordoHTMLPortion;
        }

        return $ordoHTML;
    }
}
