<?php

namespace HealthKerd\View\medic\eventsBuilder\ordo\labo;

class LaboOrdoBuilder extends LaboOrdoBuilderFunctionsPool
{
    private object $prescLaboObj;

    public function __construct()
    {
        $this->prescLaboObj = new \HealthKerd\View\medic\eventsBuilder\presc\labo\PrescLaboBuilder();
    }

    public function __destruct()
    {
    }


    public function ordoBuilder(array $singleOrdo, string $medicEventID)
    {
        $ordoHTML = '';
        $ordoArray = array();

        $ordoArray['ordoLaboAccordionStart'] = $this->ordoLaboAccordionStart($singleOrdo, $medicEventID);

        /** INSERT PRESC HERE */
        /** INSERT PRESC HERE */

        $ordoArray['presc'] = '';

        if (sizeof($singleOrdo['prescList']) > 0) {
            $allPrescsArray = array();

            foreach ($singleOrdo['prescList'] as $presc) {
                $singlePrescHTML = $this->prescLaboObj->prescBuilder($presc);
                array_push($allPrescsArray, $singlePrescHTML);
            }

            foreach ($allPrescsArray as $singlePrescHTMLPortion) {
                $ordoArray['presc'] .= $singlePrescHTMLPortion;
            }
        }



        /** INSERT PRESC HERE */
        /** INSERT PRESC HERE */

        $ordoArray['ordoLaboAccordionEnd'] = $this->ordoLaboAccordionEnd($singleOrdo, $medicEventID);

        foreach ($ordoArray as $portion) {
            $ordoHTML .= $portion;
        }

        return $ordoHTML;
    }
}
