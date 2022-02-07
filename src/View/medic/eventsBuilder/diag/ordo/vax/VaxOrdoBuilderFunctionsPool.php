<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\vax;

/** Dépot des méthodes de création de blocs pour les ordonnances vaccinales
 */
abstract class VaxOrdoBuilderFunctionsPool
{

    public function __destruct()
    {
    }

    /** Construction du début de la <div> d'une ordonnance vaccinale
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string                   Début de l'accordéon
     */
    protected function ordoVaxStarter(array $singleOrdo, string $medicEventID)
    {
        $ordoVaxStarterHTML =
            '<div id="ordo-vax-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoVaxID'] . '" class="accordion my-1 mx-1 mx-lg-2"> <!-- START OF ORDO VAX ACCORDION -->
                <div class="accordion-item"> <!-- START OF ORDO VAX ACCORDION ITEM -->
                    <h2 class="accordion-header" id="">
                        <button class="btn accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ordo-vax-content-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoVaxID'] . '">
                        <b>Ordonnance vaccinale du ' . $singleOrdo['time']['frenchDate'] . '</b>
                        </button>
                    </h2>
                    <div id="ordo-vax-content-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoVaxID'] . '" class="accordion-collapse collapse" data-bs-parent="#ordo-vax-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoVaxID'] . '"> <!-- START OF ORDO VAX ACCORDION COLLAPSE -->
                        <div class="accordion-body"> <!-- START OF ORDO VAX ACCORDION BODY -->';

        return $ordoVaxStarterHTML;
    }

    /** Construction de la <div> de commentaire de l'ordonnance vaccinale
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string                   Début de l'accordéon
     */
    protected function ordoComment(array $singleOrdo, string $medicEventID)
    {
        $ordoCommentHTML =
            '<div class="form-floating mx-0 my-1">
                <textarea class="form-control textarea-ridonli" placeholder="" id="ordo-vax-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoVaxID'] . '" readonly>' . $singleOrdo['comment'] . '</textarea>
                <label for="ordo-vax-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoVaxID'] . '">Informations complémentaires</label>
            </div>';

        return $ordoCommentHTML;
    }

    /** Construction de la fin de la <div> d'une ordonnances vaccinale
     * @return string                   Fin de l'accordéon
     */
    protected function ordoVaxEnd()
    {
        $ordoVaxEndHTML =
                        '</div> <!-- END OF ORDO VAX ACCORDION BODY -->
                    </div> <!-- END OF ORDO VAX ACCORDION COLLAPSE -->
                </div> <!-- END OF ORDO VAX ACCORDION ITEM -->
            </div> <!-- END OF ORDO VAX ACCORDION -->';

        return $ordoVaxEndHTML;
    }
}