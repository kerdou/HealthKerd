<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\vax;

/** Construction d'une ordonnance vaccinale
 */
class VaxOrdoBuilder extends VaxOrdoBuilderFunctionsPool
{
    private object $prescVaxObj;

    public function __construct()
    {
        $this->prescVaxObj = new \HealthKerd\View\medic\eventsBuilder\diag\ordo\vax\PrescVaxBuilder();
    }

    public function __destruct()
    {
    }

    /** Construction de la <div> de l'ordonnance vaccinale ainsi que ses prescriptions avec prescBuilder()
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string                   <div> de l'accordéon complet
     */
    public function ordoBuilder(array $singleOrdo, string $medicEventID)
    {
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
