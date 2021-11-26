<?php

namespace HealthKerd\View\medic\eventsBuilder\presc\labo;

class PrescLaboBuilder extends PrescLaboBuilderFunctionsPool
{
    public function prescBuilder(array $presc)
    {
        $prescHTML = '';
        $prescArray = array();

        $prescArray['prescStarter'] = $this->prescStarter($presc);

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

        $prescArray['prescComment'] = $this->prescComment($presc);
        $prescArray['prescEnd'] = $this->prescEnd($presc);

        foreach ($prescArray as $portion) {
            $prescHTML .= $portion;
        }

        return $prescHTML;
    }
}
