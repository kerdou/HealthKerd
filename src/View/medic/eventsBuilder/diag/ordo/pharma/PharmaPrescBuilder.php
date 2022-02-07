<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\pharma;

/** Construction des <div> des prescriptions pharmacologiques
 */
class PharmaPrescBuilder
{
    public function __destruct()
    {
    }

    /** Construction des <div> des prescriptions pharmacologiques
     * @param array $presc          Données de la prescription
     * @param string $medicEventID  ID de l'event (TODO: non utilisé!!!)
     * @return string               HTML de la presciption
     */
    public function prescBuilder(array $presc, string $medicEventID)
    {
        $prescHTML =
            '<div class="d-flex flex-column border border-1 rounded-3 p-2 my-1">
                <div class="mb-1">
                    <a href="#"  class="badge bg-success text-white">' . $presc['treatPharmaName'] . '</a>
                </div>
                <div class="d-flex flex-row justify-content-start flex-wrap">
                    <a href="#"  class="badge bg-warning mb-1 text-white">' . $presc['roleName'] . '</a>
                    <div class="badge bg-transparent text-dark fw-bold mx-0 mb-1">pour</div>
                    <a href="#"  class="badge bg-danger mb-1 text-white">' . $presc['affectName'] . '</a>
                </div>
                <hr class="mx-2">
                <span class="mb-2">' . $presc['content'] . '</span>
            </div>';

        return $prescHTML;
    }
}
