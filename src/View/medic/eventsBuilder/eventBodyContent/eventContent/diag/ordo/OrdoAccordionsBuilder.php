<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo;

class OrdoAccordionsBuilder
{
    public function __destruct()
    {
    }

    /**
     */
    public function buildOrder(array $ordoData): string
    {
        //var_dump($ordoData);
        $ordoAccordionHTML = '';

        if (sizeof($ordoData) > 0) {
            // construction de chacune des ordonnances puis stockage des blocs HTML
            foreach ($ordoData as $singleOrdo) {
                switch ($singleOrdo['ordoType']) {
                    case 'pharma':
                        $singleOrdoHTML = $this->pharmaOrdo($singleOrdo);
                        break;

                    case 'glass':
                        $singleOrdoHTML = $this->glassOrdo($singleOrdo);
                        break;

                    case 'lens':
                        $singleOrdoHTML = $this->lensOrdo($singleOrdo);
                        break;

                    case 'laboSampling':
                        $singleOrdoHTML = $this->laboOrdo($singleOrdo);
                        break;

                    case 'vax':
                        $singleOrdoHTML = $this->vaxOrdo($singleOrdo);
                        break;
                }

                $ordoAccordionHTML .= $singleOrdoHTML;
            }
        }

        return $ordoAccordionHTML;
    }



    /** Construction de l'accordéon d'une ordonnance pharmacologique
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string
     */
    private function pharmaOrdo(array $singleOrdo)
    {
        $pharmaOrdoObj = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\pharma\PharmaOrdoBuilder();
        $pharmaOrdoHTML = $pharmaOrdoObj->ordoBuilder($singleOrdo);
        return $pharmaOrdoHTML;
    }

    /** Construction de l'accordéon d'une ordonnance de lunettes
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string
     */
    private function glassOrdo(array $singleOrdo)
    {
        $glassOrdoObj = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\glass\GlassOrdoBuilder();
        $glassOrdoHTML = $glassOrdoObj->ordoBuilder($singleOrdo);
        return $glassOrdoHTML;
    }

    /** Construction de l'accordéon d'une ordonnance de lentilles
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string
     */
    private function lensOrdo(array $singleOrdo)
    {
        $lensOrdoObj = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\lens\LensOrdoBuilder();
        $lensOrdoHTML = $lensOrdoObj->ordoBuilder($singleOrdo);
        return $lensOrdoHTML;
    }

    /** Construction de l'accordéon d'une ordonnance de preélèvement en laboratoire médical
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string
     */
    private function laboOrdo(array $singleOrdo)
    {
        $laboOrdoObj = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\labo\LaboOrdoBuilder();
        $laboOrdoHTML = $laboOrdoObj->ordoBuilder($singleOrdo);
        return $laboOrdoHTML;
    }

    /** Construction de l'accordéon d'une ordonnance vaccinale
     * @param array $singleOrdo         Données de l'ordonnance
     * @return string
     */
    private function vaxOrdo(array $singleOrdo)
    {
        $vaxOrdoObj = new \HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\ordo\vax\VaxOrdoBuilder();
        $vaxOrdoHTML = $vaxOrdoObj->ordoBuilder($singleOrdo);
        return $vaxOrdoHTML;
    }
}
