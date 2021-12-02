<?php

namespace HealthKerd\View\medic\eventsBuilder\ordo\lens;

class LensOrdoBuilder
{

    public function __destruct()
    {
    }


    public function ordoBuilder(array $singleOrdo, string $medicEventID)
    {
        $lensOrdoHTML =
            '<div id="ordo-lens-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '" class="accordion mt-3"> <!-- ordoSightID= ' . $singleOrdo['ordoSightID'] . ' START OF ORDO LENS ACCORDION -->
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

                            <div class="form-floating mx-0 mt-2">
                                <textarea class="form-control" placeholder="" id="ordo-lens-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '">' . $singleOrdo['comment'] . '</textarea>
                                <label for="ordo-lens-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '">Informations complémentaires</label>
                            </div>

                        </div> <!-- END OF ORDO LENS ACCORDION BODY -->
                    </div> <!-- END OF ORDO LENS ACCORDION COLLAPSE -->
                </div> <!-- END OF ORDO LENS ACCORDION ITEM -->
            </div> <!-- END OF ORDO LENS ACCORDION -->';

        return $lensOrdoHTML;
    }
}
