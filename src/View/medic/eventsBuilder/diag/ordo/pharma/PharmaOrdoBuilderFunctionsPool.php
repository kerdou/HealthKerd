<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\pharma;

/** Dépot des méthodes de création de blocs pour les ordonnances pharmacologiques
 */
abstract class PharmaOrdoBuilderFunctionsPool
{
    public function __destruct()
    {
    }

    /** Construction du début de la <div> d'une ordonnance pharmacologique
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string                   Début de l'accordéon
     */
    protected function ordoPharmaStarter(array $singleOrdo, string $medicEventID)
    {
        $ordoPharmaStarterHTML =
            '<div id="ordo-pharma-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '" class="accordion my-1 mx-1 mx-lg-2"> <!-- ordoPharmaID = ' . $singleOrdo['ordoPharmaID'] . ' START OF ORDO PHARMA ACCORDION -->
                <div class="accordion-item"> <!-- START OF ORDO PHARMA ACCORDION ITEM -->
                    <h2 class="accordion-header" id="">
                        <button class="btn accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ordo-pharma-content' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '">
                        <b>Ordonnance pharmacologique du ' . $singleOrdo['time']['frenchDate'] . '</b>
                        </button>
                    </h2>
                    <div id="ordo-pharma-content' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '" class="accordion-collapse collapse" data-bs-parent="#ordo-pharma-accordion' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '"> <!-- START OF ORDO PHARMA ACCORDION COLLAPSE -->
                        <div class="accordion-body"> <!-- START OF ORDO PHARMA ACCORDION BODY -->';

        return $ordoPharmaStarterHTML;
    }

    /** Construction de la <div> de commentaire de l'ordonnance pharmacologique
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string                   Début de l'accordéon
     */
    protected function ordoPharmaComment(array $singleOrdo, string $medicEventID)
    {
        $ordoPharmaCommentHTML =
            '<div class="form-floating mx-0 mt-2">
                <textarea class="form-control textarea-ridonli" placeholder="" id="ordo-pharma-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '" readonly>' . $singleOrdo['comment'] .  '</textarea>
                <label for="ordo-pharma-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '">Informations complémentaires</label>
            </div>';

        return $ordoPharmaCommentHTML;
    }

    /** Construction de la fin de la <div> d'une ordonnances pharmacologique
     * @return string                   Fin de l'accordéon
     */
    protected function ordoPharmaEnd()
    {
        $ordoPharmaEndHTML =
                        '</div> <!-- END OF ORDO PHARMA ACCORDION BODY -->
                    </div> <!-- END OF ORDO PHARMA ACCORDION COLLAPSE -->
                </div> <!-- END OF ORDO PHARMA ACCORDION ITEM -->
            </div> <!-- END OF ORDO PHARMA ACCORDION --> ';

        return $ordoPharmaEndHTML;
    }
}