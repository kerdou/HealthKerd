<?php

namespace HealthKerd\Processor\medic\ordo;

class OrdoGatherAndSorting
{
    private array|null $ordoLabo = array();
    private array|null $ordoPharma = array();
    private array|null $ordoVax = array();
    private array|null $ordoSight = array();
    private array $ordoGlobalArray = array();

    /**
     *
     */
    public function ordoArrayGeneralBuildOrder(
        array|null $ordoLabo,
        array|null $ordoPharma,
        array|null $ordoVax,
        array|null $ordoSight
    ) {
        $this->ordoLabo = $ordoLabo;
        $this->ordoPharma = $ordoPharma;
        $this->ordoVax = $ordoVax;
        $this->ordoSight = $ordoSight;

        $this->allInOneArray($this->ordoLabo);
        $this->allInOneArray($this->ordoPharma);
        $this->allInOneArray($this->ordoVax);
        $this->allInOneArray($this->ordoSight);

        uasort($this->ordoGlobalArray, array($this, "timestampSorting")); // tri des ordonnances par ordre croissant des timestamps

        //echo '<pre>';
        //print_r($this->ordoLabo);
        //print_r($this->ordoPharma);
        //print_r($this->ordoVax);
        //print_r($this->ordoSight);
        //print_r($this->ordoGlobalArray);
        //echo '</pre>';

        return $this->ordoGlobalArray;
    }


    /**
     *
     */
    private function allInOneArray(array $arrayToPush)
    {
        foreach ($arrayToPush as $value) {
            array_push($this->ordoGlobalArray, $value);
        }
    }



    /** Tri des ordonnances par timestamp en ordre croissant */
    private function timestampSorting(array $firstValue, array $secondValue)
    {
        if ($firstValue['time']['timestamp'] == $secondValue['time']['timestamp']) {
            return 0;
        }
        return ($firstValue['time']['timestamp'] < $secondValue['time']['timestamp']) ? -1 : 1;
    }
}
