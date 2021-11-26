<?php

namespace HealthKerd\View\medic\eventsBuilder\diag;

class DiagBuilder extends DiagBuilderFunctionsPool
{
    private object|null $pharmaOrdoObj;
    private object|null $glassOrdoObj;
    private object|null $lensOrdoObj;
    private object|null $laboOrdoObj;
    private object|null $vaxOrdoObj;

    public function __construct()
    {
        $this->pharmaOrdoObj = new \HealthKerd\View\medic\eventsBuilder\ordo\pharma\PharmaOrdoBuilder();
        $this->glassOrdoObj = new \HealthKerd\View\medic\eventsBuilder\ordo\glass\GlassOrdoBuilder();
        $this->lensOrdoObj = new \HealthKerd\View\medic\eventsBuilder\ordo\lens\LensOrdoBuilder();
        $this->laboOrdoObj = new \HealthKerd\View\medic\eventsBuilder\ordo\labo\LaboOrdoBuilder();
        $this->vaxOrdoObj = new \HealthKerd\View\medic\eventsBuilder\ordo\vax\VaxOrdoBuilder();
    }

    public function diagBuilder(array $value)
    {
        //echo '<pre>';
        //print_r($value);
        //echo '</pre>';

        $diagHTML = '';
        $diagArray = array();

        $diagArray['diagStart'] = $this->diagAccordionStart($value);
        $diagArray['diagSymptoms'] = $this->diagSymptomsBuilder($value['content']['diag']['symptoms']);
        $diagArray['diagBetweenSymptAndChecks'] = $this->diagBetweenSymptAndChecks();
        $diagArray['diagChecks'] = $this->diagChecks($value['content']['diag']['checkpoints']);
        $diagArray['diagBetweenChecksAndConclu'] = $this->diagBetweenChecksAndConclu();
        $diagArray['diagConclu'] = $this->diagConclu($value['content']['diag']['conclusions']);
        $diagArray['diagComment'] = $this->diagComment($value);

        /** INSERT ORDO CONTENT HERE */
        /** INSERT ORDO CONTENT HERE */

        $diagArray['diagOrdo'] = '';

        if (sizeof($value['content']['diag']['ordo']) > 0) {
            $allOrdosArray = array();

            foreach ($value['content']['diag']['ordo'] as $singleOrdo) {
                $singleOrdoHTML = '';

                switch ($singleOrdo['ordoType']) {
                    case 'pharma':
                        $singleOrdoHTML = $this->pharmaOrdo($singleOrdo, $value['medicEventID']);
                        break;

                    case 'glass':
                        $singleOrdoHTML = $this->glassOrdo($singleOrdo, $value['medicEventID']);
                        break;

                    case 'lens':
                        $singleOrdoHTML = $this->lensOrdo($singleOrdo, $value['medicEventID']);
                        break;

                    case 'laboSampling':
                        $singleOrdoHTML = $this->laboOrdo($singleOrdo, $value['medicEventID']);
                        break;

                    case 'vax':
                        $singleOrdoHTML = $this->vaxOrdo($singleOrdo, $value['medicEventID']);
                        break;
                }

                array_push($allOrdosArray, $singleOrdoHTML);
            }

            foreach ($allOrdosArray as $singleOrdoHTMLPortion) {
                $diagArray['diagOrdo'] .= $singleOrdoHTMLPortion;
            }
        }


        /** INSERT ORDO CONTENT HERE */
        /** INSERT ORDO CONTENT HERE */

        $diagArray['diagAccordionEnd'] = $this->diagAccordionEnd($value);

        foreach ($diagArray as $portion) {
            $diagHTML .= $portion;
        }

        return $diagHTML;
    }


    /** */
    private function pharmaOrdo(array $singleOrdo, string $medicEventID)
    {
        $pharmaOrdoHTML = $this->pharmaOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $pharmaOrdoHTML;
    }


    /** */
    private function glassOrdo(array $singleOrdo, string $medicEventID)
    {
        $glassOrdoHTML = $this->glassOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $glassOrdoHTML;
    }


    /** */
    private function lensOrdo(array $singleOrdo, string $medicEventID)
    {
        $lensOrdoHTML = $this->lensOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $lensOrdoHTML;
    }


    /** */
    private function laboOrdo(array $singleOrdo, string $medicEventID)
    {
        $laboOrdoHTML = $this->laboOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $laboOrdoHTML;
    }


    /** */
    private function vaxOrdo(array $singleOrdo, string $medicEventID)
    {
        $vaxOrdoHTML = $this->vaxOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $vaxOrdoHTML;
    }
}
