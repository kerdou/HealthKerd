<?php

namespace HealthKerd\View\medic\eventsBuilder\ordo\pharma;

abstract class PharmaOrdoBuilderFunctionsPool
{
    /** */
    protected function ordoPharmaStarter(array $singleOrdo, string $medicEventID)
    {
        $ordoPharmaStarterHTML =
            '<div id="ordo-pharma-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '" class="accordion mt-3"> <!-- ordoPharmaID = ' . $singleOrdo['ordoPharmaID'] . ' START OF ORDO PHARMA ACCORDION -->
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


    /** */
    protected function ordoPharmaComment(array $singleOrdo, string $medicEventID)
    {
        $ordoPharmaCommentHTML =
            '<div class="form-floating mx-0 mt-2">
                <textarea class="form-control" placeholder="" id="ordo-pharma-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '">' . $singleOrdo['comment'] .  '</textarea>
                <label for="ordo-pharma-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoPharmaID'] . '">Informations complémentaires</label>
            </div>';

        return $ordoPharmaCommentHTML;
    }


    /** */
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
