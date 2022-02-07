<?php

namespace HealthKerd\View\medic\eventsBuilder\diag;

/** Construction de l'accordéon de diagnostic et des sous-accordéons des contenus suivants:
 * * Ordonnances pharmacologiques
 * * Ordonnances de lunettes
 * * Ordonnances de lentilles
 * * Ordonnances de prélèvements en labortoire médical
 * * Ordonnances vaccinales
 */
class DiagBuilder extends DiagBuilderFunctionsPool
{
    private object $pharmaOrdoObj;
    private object $glassOrdoObj;
    private object $lensOrdoObj;
    private object $laboOrdoObj;
    private object $vaxOrdoObj;


    public function __construct()
    {
        $this->pharmaOrdoObj = new \HealthKerd\View\medic\eventsBuilder\diag\ordo\pharma\PharmaOrdoBuilder();
        $this->glassOrdoObj = new \HealthKerd\View\medic\eventsBuilder\diag\ordo\glass\GlassOrdoBuilder();
        $this->lensOrdoObj = new \HealthKerd\View\medic\eventsBuilder\diag\ordo\lens\LensOrdoBuilder();
        $this->laboOrdoObj = new \HealthKerd\View\medic\eventsBuilder\diag\ordo\labo\LaboOrdoBuilder();
        $this->vaxOrdoObj = new \HealthKerd\View\medic\eventsBuilder\diag\ordo\vax\VaxOrdoBuilder();
    }

    public function __destruct()
    {
    }

    /** Construction de l'accordéon de diagnostic et des sous-accordéons des contenus suivants:
     * * Ordonnances pharmacologiques
     * * Ordonnances de lunettes
     * * Ordonnances de lentilles
     * * Ordonnances de prélèvements en labortoire médical
     * * Ordonnances vaccinales
     * @param array $value      Données du diagnostic
     * @return string           HTML de l'accordéon de diagnostic et de ses contenus
     */
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

            // construction de chacune des ordonnances puis stockage des blocs HTML dans $diagArray['diagOrdo']
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

    /** Construction de l'accordéon d'une ordonnance pharmacologique
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string
     */
    private function pharmaOrdo(array $singleOrdo, string $medicEventID)
    {
        $pharmaOrdoHTML = $this->pharmaOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $pharmaOrdoHTML;
    }

    /** Construction de l'accordéon d'une ordonnance de lunettes
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string
     */
    private function glassOrdo(array $singleOrdo, string $medicEventID)
    {
        $glassOrdoHTML = $this->glassOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $glassOrdoHTML;
    }

    /** Construction de l'accordéon d'une ordonnance de lentilles
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string
     */
    private function lensOrdo(array $singleOrdo, string $medicEventID)
    {
        $lensOrdoHTML = $this->lensOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $lensOrdoHTML;
    }

    /** Construction de l'accordéon d'une ordonnance de preélèvement en laboratoire médical
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string
     */
    private function laboOrdo(array $singleOrdo, string $medicEventID)
    {
        $laboOrdoHTML = $this->laboOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $laboOrdoHTML;
    }

    /** Construction de l'accordéon d'une ordonnance vaccinale
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string
     */
    private function vaxOrdo(array $singleOrdo, string $medicEventID)
    {
        $vaxOrdoHTML = $this->vaxOrdoObj->ordoBuilder($singleOrdo, $medicEventID);
        return $vaxOrdoHTML;
    }
}
