<?php

namespace HealthKerd\Processor\medic\presc;

class PrescOrdoVaxOrganizer
{
    private array|null $prescVaxList = array();
    private array|null $treatPharmaList = array();

    public function prescOrdoVaxGeneralBuildOrder(
        array|null $prescVaxList,
        array|null $treatPharmaList
    ) {
        $this->prescVaxList = $prescVaxList;
        $this->treatPharmaList = $treatPharmaList;

        $this->treatmentAdder();

        //var_dump($this->prescVaxList);
        //var_dump($this->treatPharmaList);

        return $this->prescVaxList;
    }

    private function treatmentAdder()
    {
        foreach ($this->prescVaxList as $prescKey => $prescValue) {
            foreach ($this->treatPharmaList as $treatKey => $treatValue) {
                if ($prescValue['treatPharmaID'] == $treatValue['treatPharmaID']) {
                    $this->prescVaxList[$prescKey]['treatPharmaName'] = $treatValue['name'];
                }
            }
        }
    }
}
