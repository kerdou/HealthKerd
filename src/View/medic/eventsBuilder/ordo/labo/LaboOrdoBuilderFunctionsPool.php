<?php

namespace HealthKerd\View\medic\eventsBuilder\ordo\labo;

abstract class LaboOrdoBuilderFunctionsPool
{
    public function __destruct()
    {
    }


    /** */
    protected function ordoLaboAccordionStart(array $singleOrdo, string $medicEventID)
    {
        $ordoLaboAccordionStartHTML =
        '<div id="ordo-labo-accordion-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoLaboID'] . '" class="accordion mt-3"> <!-- ordoLaboID = ' . $singleOrdo['ordoLaboID'] . ' START OF ORDO LABO ACCORDION -->
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

    protected function ordoLaboAccordionEnd(array $singleOrdo, string $medicEventID)
    {
        $ordoLaboAccordionEndHTML =
                            '<div class="form-floating mx-0 mt-2">
                                <textarea class="form-control" placeholder="" id="ordo-labo-textarea-' . $medicEventID . '-' . $singleOrdo['diagID'] . '-' . $singleOrdo['ordoLaboID'] . '">' . $singleOrdo['comment'] . '</textarea>
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
