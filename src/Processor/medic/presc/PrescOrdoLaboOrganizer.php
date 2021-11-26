<?php

namespace HealthKerd\Processor\medic\presc;

class PrescOrdoLaboOrganizer
{
    private array|null $prescLaboList = array();
    private array|null $prescLaboElements = array();

    public function prescOrdoLaboGeneralBuildOrder(
        array|null $prescLaboList,
        array|null $prescLaboElements
    ) {
        $this->prescLaboList = $prescLaboList;
        $this->prescLaboElements = $prescLaboElements;

        $this->prescContentOrganizer();
        $this->prescElementsAddition();

        //var_dump($this->prescLaboList);
        //var_dump($this->prescLaboElements);

        /*
        echo '<pre>';
        print_r($this->prescLaboList);
        echo '</pre>';
        */

        return $this->prescLaboList;
    }

    private function prescContentOrganizer()
    {
        foreach ($this->prescLaboList as $key => $value) {
            $this->prescLaboList[$key]['elements'] = array();
        }
    }

    private function prescElementsAddition()
    {
        foreach ($this->prescLaboList as $prescKey => $prescValue) {
            foreach ($this->prescLaboElements as $elemKey => $elemenValue) {
                if ($prescValue['prescLaboID'] == $elemenValue['prescLaboID']) {
                    array_push($this->prescLaboList[$prescKey]['elements'], $elemenValue);
                }
            }
        }
    }
}
