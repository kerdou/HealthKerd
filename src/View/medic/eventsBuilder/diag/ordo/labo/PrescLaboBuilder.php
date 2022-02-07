<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\labo;

/** Construction des <div> des prescriptions de prélévement en laboratoire médical
 */
class PrescLaboBuilder extends PrescLaboBuilderFunctionsPool
{
    public function __destruct()
    {
    }

    /** Construction des <div> des prescriptions de prélévement en laboratoire médical
     * @param array $presc      Données de la prescription
     * @return string           HTML de la presciption
     */
    public function prescBuilder(array $presc)
    {
        $prescHTML = '';
        $prescArray = array();

        $prescArray['prescStarter'] = $this->prescStarter($presc['samplingType']);

        $prescArray['prescElem'] = '';
        if (sizeof($presc['elements']) > 0) {
            $allPrescElemArray = array();

            foreach ($presc['elements'] as $element) {
                $li = '<li>' . $element['elementContent'] . '</li>';
                array_push($allPrescElemArray, $li);
            }

            foreach ($allPrescElemArray as $elementHTMLPortion) {
                $prescArray['prescElem'] .= $elementHTMLPortion;
            }
        }

        $prescArray['prescEnd'] = $this->prescEnd();

        foreach ($prescArray as $portion) {
            $prescHTML .= $portion;
        }

        return $prescHTML;
    }
}
