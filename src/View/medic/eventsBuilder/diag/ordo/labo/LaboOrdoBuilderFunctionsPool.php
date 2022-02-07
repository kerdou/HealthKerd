<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\labo;

/** Dépot des méthodes de création de blocs pour les ordonnances de prélévement en laboratoire médical
 */
abstract class LaboOrdoBuilderFunctionsPool
{
    public function __destruct()
    {
    }

    /** Construction du début de la <div> de prélévement en laboratoire médical
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string                   Début de l'accordéon
     */
    protected function ordoLaboAccordionStart(array $singleOrdo, string $medicEventID)
    {
        $ordoLaboAccordionStartHTML =
        '<div id="ordo-labo-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoLaboID'] . '" class="accordion my-1 mx-1 mx-lg-2"> <!-- ordoLaboID = ' . $singleOrdo['ordoLaboID'] . ' START OF ORDO LABO ACCORDION -->
            <div class="accordion-item"> <!-- START OF ORDO LABO ACCORDION ITEM -->
                <h2 class="accordion-header" id="">
                    <button class="btn accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ordo-labo-content-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoLaboID'] . '">
                        <b>Ordonnance de prélèvement du ' . $singleOrdo['time']['frenchDate'] . '</b>
                    </button>
                </h2>
                <div id="ordo-labo-content-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoLaboID'] . '" class="accordion-collapse collapse" data-bs-parent="#ordo-labo-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoLaboID'] . '"> <!-- START OF ORDO LABO ACCORDION COLLAPSE -->
                    <div class="accordion-body"> <!-- START OF ORDO LABO ACCORDION BODY -->';

        return $ordoLaboAccordionStartHTML;
    }

    /** Construction de la fin de la <div> de prélévement en laboratoire médical
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string                   Fin de l'accordéon
     */
    protected function ordoLaboAccordionEnd(array $singleOrdo, string $medicEventID)
    {
        $ordoLaboAccordionEndHTML =
                            '<div class="form-floating mx-0 my-1">
                                <textarea class="form-control textarea-ridonli" placeholder="" id="ordo-labo-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoLaboID'] . '" readonly>' . $singleOrdo['comment'] . '</textarea>
                                <label for="ordo-labo-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoLaboID'] . '">Informations complémentaires</label>
                            </div>

                        </div> <!-- END OF ORDO LABO ACCORDION BODY -->
                    </div> <!-- END OF ORDO LABO ACCORDION COLLAPSE -->
                </div> <!-- END OF ORDO LABO ACCORDION ITEM -->
            </div> <!-- END OF ORDO LABO ACCORDION -->
            ';

        return $ordoLaboAccordionEndHTML;
    }
}
