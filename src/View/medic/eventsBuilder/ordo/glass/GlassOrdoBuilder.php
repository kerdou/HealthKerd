<?php

namespace HealthKerd\View\medic\eventsBuilder\ordo\glass;

class GlassOrdoBuilder
{

    public function __destruct()
    {
    }

    public function ordoBuilder(array $singleOrdo, string $medicEventID)
    {
        $glassOrdoHTML =
            '<div id="ordo-glass-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '" class="accordion my-1 mx-1 mx-lg-2"> <!-- ordoSightID= ' . $singleOrdo['ordoSightID'] . ' START OF ORDO GLASS ACCORDION -->
                <div class="accordion-item"> <!-- START OF ORDO GLASS ACCORDION ITEM -->
                    <h2 class="accordion-header" id="">
                        <button class="btn accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ordo-glass-content-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '"">
                            <b>Ordonnance de lunettes du ' . $singleOrdo['time']['frenchDate'] . '</b>
                        </button>
                    </h2>
                    <div id="ordo-glass-content-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '" class="accordion-collapse collapse" data-bs-parent="#ordo-glass-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '""> <!-- START OF ORDO GLASS ACCORDION COLLAPSE -->
                        <div class="accordion-body p-2 p-lg-3"> <!-- START OF ORDO GLASS ACCORDION BODY -->

                            <div class="d-flex flex-column border border-1 rounded-3 p-2">
                                <div class="d-flex flex-row mt-2">
                                    <div class="me-1">&Eacute;cartement pupillaire:</div><div>' . $singleOrdo['pupDist'] . 'mm</div>
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
                                <textarea class="form-control textarea-ridonli" placeholder="" id="ordo-glass-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '">' . $singleOrdo['comment'] . '</textarea>
                                <label for="ordo-glass-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoSightID'] . '">Informations complémentaires</label>
                            </div>

                        </div> <!-- END OF ORDO GLASS ACCORDION BODY -->
                    </div> <!-- END OF ORDO GLASS ACCORDION COLLAPSE -->
                </div> <!-- END OF ORDO GLASS ACCORDION ITEM -->
            </div> <!-- END OF ORDO GLASS ACCORDION -->';

            return $glassOrdoHTML;
    }
}
