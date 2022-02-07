<?php

namespace HealthKerd\View\medic\eventsBuilder\diag;

/** Dépot des méthodes servant à la construction de l'accordéon de diagnostic
 */
abstract class DiagBuilderFunctionsPool
{
    public function __destruct()
    {
    }

    /** Début de l'accordéon de diagnostic
     * @param array $value      Données du diagnostic
     * @return string           HTML du début de l'accordéon de diagnostic
     */
    protected function diagAccordionStart(array $value)
    {
        $diagAccordionStartHTML =
        '<div id="diag-accordion-' . $value['medicEventID'] . '-' . $value['content']['diag']['diagID'] . '" class="accordion my-1"> <!-- diagID = ' . $value['content']['diag']['diagID'] . '  START OF DIAG ACCORDION -->
            <div class="accordion-item"> <!-- START OF DIAG ACCORDION ITEM -->
                <h2 class="accordion-header" id="diag-header"> <!-- START OF DIAG ACCORDION HEADER -->
                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#diag-content-' . $value['medicEventID'] . '-' . $value['content']['diag']['diagID'] . '">
                    <b>Diagnostic & ordonnances</b>
                    </button>
                </h2>
                <div id="diag-content-' . $value['medicEventID'] . '-' . $value['content']['diag']['diagID'] . '" class="accordion-collapse collapse" data-bs-parent="#diag-accordion-' . $value['medicEventID'] . '-' . $value['content']['diag']['diagID'] . '">  <!-- START OF DIAG ACCORDION CONTENT -->
                    <div class="accordion-body p-1">  <!-- START OF DIAG ACCORDION BODY -->

                        <div class="p-2">
                            <div class="d-flex flex-column flex-xl-row justify-content-start">
                                <div class="flex-fill">
                                    <h5><u>Symptômes</u></h5>
                                    <ul>';

        return $diagAccordionStartHTML;
    }

    /** Construction des <li> des symptômes des diagnostics
     * @param array $symptoms       Liste des symptômes
     * @return string               <li> des symptômes concaténés
     */
    protected function diagSymptomsBuilder(array $symptoms)
    {
        $diagSymptHTML = '';
        $diagSymptArray = array();

        foreach ($symptoms as $value) {
            $li = '<li class="">' . $value['symptom'] . '</li>';
            array_push($diagSymptArray, $li);
        }


        if (sizeof($diagSymptArray) > 0) {
            foreach ($diagSymptArray as $liHTML) {
                $diagSymptHTML .= $liHTML;
            }
        } else {
            $diagSymptHTML = '<li class="">Aucun symptôme signalé</li>';
        }

        return $diagSymptHTML;
    }

    /** Bloc intermédiaire entre le <ul> des diagnostics et le <ul> des points de contrôle du diagnostic
     * @return string       HTML du bloc intermédiaire
     */
    protected function diagBetweenSymptAndChecks()
    {
        $diagBetweenSymptAndChecksHTML =
                '</ul>
            </div>

            <div class="flex-fill">
                <h5><u>Points de contrôle</u></h5>
                <ul>';

        return $diagBetweenSymptAndChecksHTML;
    }

    /** Construction des <li> de points de contrôle du diagnostic
     * @param array $checks     Liste des points de contrôle du diagnostic
     * @return string           <li> des points de contrôle concaténés
     */
    protected function diagChecks(array $checks)
    {
        $diagChecksHTML = '';
        $diagChecksArray = array();

        foreach ($checks as $value) {
            $li = '<li class="">' . $value['checkpoint'] . '</li>';
            array_push($diagChecksArray, $li);
        }

        if (sizeof($diagChecksArray) > 0) {
            foreach ($diagChecksArray as $liHTML) {
                $diagChecksHTML .= $liHTML;
            }
        } else {
            $diagChecksHTML = '<li class="">Aucun point de contrôle signalé</li>';
        }

        return $diagChecksHTML;
    }

    /** Bloc intermédiaire entre le <ul> des points de contrôle et le <h5> des conclusions du diagnostic
     * @return string       HTML du bloc intermédiaire
     */
    protected function diagBetweenChecksAndConclu()
    {
        $diagBetweenChecksAndConcluHTML =
                '</ul>
                </div>
            </div>

            <div>
                <h5><u>Conclusions</u></h5>';

        return $diagBetweenChecksAndConcluHTML;
    }

    /** Construction du début du <ul> des conclusions de diagnostics et de chacun de ses <li>
     * * Lance également sickDetailedStatusBadge() pour la construction des statuts des maladies
     * @param array $conclu     Liste des conslusions des diagnostics
     * @return string           HTML du début du <ul> et des <li> concaténés
     */
    protected function diagConclu(array $conclu)
    {
        $concluHTML = '';
        $concluArray = array();

        foreach ($conclu as $value) {
            $affectBadge = '<a href="#" class="badge bg-danger mb-1 me-1 text-white ">' . $value['medicAffectName'] . '</a>';
            $sickStatusBadge = $this->sickDetailedStatusBadge($value['sickDetailedStatus']);
            $li =
                '<li class="d-flex flex-row justify-content-start flex-wrap">'
                    . $affectBadge . ''
                    . $sickStatusBadge .
                '</li>';

            array_push($concluArray, $li);
        }

        if (sizeof($concluArray) > 0) {
            $concluHTML .= '<ul class="ps-0">';

            foreach ($concluArray as $liHTML) {
                $concluHTML .= $liHTML;
            }
        } else {
            $concluHTML = '<ul class="">';
            $concluHTML .= '<li class="">Pas de conclusion signalée</li>';
        }

        return $concluHTML;
    }

    /** Conversion des strings de statut des maladie puis création de leur badges
     * @param string $sickDetailedStatus    Statut de la maladie
     * @return string                       DIV du badge de la maladie
     */
    private function sickDetailedStatusBadge(string $sickDetailedStatus)
    {
        $sickStatusString = '';

        switch ($sickDetailedStatus) {
            case 'neverHad':
                $sickStatusString = 'Jamais eu';
                break;

            case 'suspected':
                $sickStatusString = 'Suspecté';
                break;

            case 'refuted':
                $sickStatusString = 'Réfuté';
                break;

            case 'confirmed':
                $sickStatusString = 'Confirmé';
                break;

            case 'stillSickButMonitored':
                $sickStatusString = 'Toujours affecté et suivi';
                break;

            case 'healedButMonitored':
                $sickStatusString = 'Guéri mais surveillé';
                break;

            case 'healed':
                $sickStatusString = 'Guéri';
                break;
        }

        $sickStatusBadge = '<div class="badge bg-info text-white mx-0 mb-1">' . $sickStatusString . '</div>';

        return $sickStatusBadge;
    }

    /** Fin du <ul> des conclusions du diagnostic puis création de la <div> de commentaire
     * @param array $value      Données du diagnostic
     * @return string           HTML de la fin du <ul> des conclusions de diagnostic suivi de a <div> de commentaire
     */
    protected function diagComment(array $value)
    {
        $diagCommentHTML =
                    '</ul>
                </div>

                <div class="form-floating mx-0 my-1">
                    <textarea class="form-control textarea-ridonli" placeholder="" id="diag-textarea-' . $value['medicEventID'] . '-' . $value['content']['diag']['diagID'] . '" readonly>' . $value['content']['diag']['comment'] . '</textarea>
                    <label for="diag-textarea-' . $value['medicEventID'] . '-' . $value['content']['diag']['diagID'] . '">Informations complémentaires sur le diagnostic</label>
                </div>
            </div>';

        return $diagCommentHTML;
    }

    /** Fin de l'accordéon de diagnostic
     * @return string       HTML des fins de <div>
     */
    protected function diagAccordionEnd()
    {
        $diagAccordionEndHTML =
                    '</div> <!-- END OF DIAG ACCORDION BODY -->
                </div> <!-- END OF DIAG ACCORDION CONTENT -->
            </div> <!-- END OF DIAG ACCORDION ITEM -->
        </div> <!-- END OF DIAG ACCORDION -->';

        return $diagAccordionEndHTML;
    }
}
