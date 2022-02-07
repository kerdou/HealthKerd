<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\vax;

/** Construction des <div> des prescriptions d'ordonnance vaccinale
 */
class PrescVaxBuilder
{
    public function __destruct()
    {
    }

    /** Construction des <div> des prescriptions d'ordonnance vaccinale
     * @param array $presc      Données de la prescription
     * @return string           HTML de la presciption
     */
    public function prescBuilder(array $presc)
    {
        $prescHTML =
            '<div class="d-flex flex-column border border-1 rounded-3 p-2 my-1">
                <div class="d-flex flex-row justify-content-start flex-wrap">
                    <a href="#" class="badge bg-success mb-1">' . $presc['name'] . '</a>
                </div>
                <hr class="mx-2">
                <span class="mb-2">' . $presc['comment'] . '</span>
            </div>';

        return $prescHTML;
    }
}