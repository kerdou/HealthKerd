<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\lens;

/** Construction d'une ordonnance de lunettes
 */
class LensOrdoBuilder
{

    public function __destruct()
    {
    }

    /** Construction de la <div> de l'ordonnance de lentille
     * @param array $singleOrdo         Données de l'ordonnance
     * @param string $medicEventID      ID de l'event
     * @return string                   <div> de l'accordéon complet
     */
    public function ordoBuilder(array $singleOrdo, string $medicEventID)
    {
        $lensOrdoHTML =
            '<div id="ordo-lens-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '" class="accordion my-1 mx-1 mx-lg-2"> <!-- ordoSightID= ' . $singleOrdo['ordoSightID'] . ' START OF ORDO LENS ACCORDION -->
                <div class="accordion-item"> <!-- START OF ORDO LENS ACCORDION ITEM -->
                    <h2 class="accordion-header" id="">
                        <button class="btn accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ordo-lens-content-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '">
                        <b>Ordonnance de lentilles du ' . $singleOrdo['time']['frenchDate'] . '</b>
                        </button>
                    </h2>
                    <div id="ordo-lens-content-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '" class="accordion-collapse collapse" data-bs-parent="#ordo-lens-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '"> <!-- START OF ORDO LENS ACCORDION COLLAPSE -->
                        <div class="accordion-body"> <!-- START OF ORDO LENS ACCORDION BODY -->

                            <div class="d-flex flex-column border border-1 rounded-3 p-1 p-2">
                                <div class="d-flex flex-row mt-2">
                                    <div class="me-1">Modèle:</div><div>' . $singleOrdo['lens']['model'] . '</div>
                                </div>
                                <div class="d-flex flex-row mt-2">
                                    <div class="me-1">Diamètre:</div><div>' . $singleOrdo['lens']['diameter'] . '</div>
                                </div>
                                <div class="d-flex flex-row mt-2">
                                    <div class="me-1">Rayon:</div><div>' . $singleOrdo['lens']['radius'] . '</div>
                                </div>
                                <hr class="mx-2">
                                <div class="d-flex flex-row">
                                    <div class="me-1">Oeil droit:</div><div>' . $singleOrdo['rightEye']['sentence'] . '</div>
                                </div>
                                <div class="d-flex flex-row">
                                    <div class="me-1">Oeil gauche:</div><div>' . $singleOrdo['leftEye']['sentence'] . '</div>
                                </div>
                            </div>

                            <div class="form-floating mx-0 my-1">
                                <textarea class="form-control textarea-ridonli" placeholder="" id="ordo-lens-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '" readonly>' . $singleOrdo['comment'] . '</textarea>
                                <label for="ordo-lens-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '">Informations complémentaires</label>
                            </div>

                        </div> <!-- END OF ORDO LENS ACCORDION BODY -->
                    </div> <!-- END OF ORDO LENS ACCORDION COLLAPSE -->
                </div> <!-- END OF ORDO LENS ACCORDION ITEM -->
            </div> <!-- END OF ORDO LENS ACCORDION -->';

        return $lensOrdoHTML;
    }
}