<?php

namespace HealthKerd\View\medic\eventsBuilder\presc\pharma;

class PharmaPrescBuilder
{
    public function __destruct()
    {
    }


    public function prescBuilder(array $presc, string $medicEventID)
    {
        $prescHTML =
            '<div class="d-flex flex-column border border-1 rounded-3 p-2 my-1">
                <div class="mb-1">
                    <a href="#"  class="badge bg-success">' . $presc['treatName'] . '</a>
                </div>
                <div class="d-flex flex-row justify-content-start flex-wrap">
                    <a href="#"  class="badge bg-warning mb-1">' . $presc['roleName'] . '</a>
                    <div class="badge bg-transparent text-dark fw-bold mx-0 mb-1">pour</div>
                    <a href="#"  class="badge bg-danger mb-1">' . $presc['affectName'] . '</a>
                </div>
                <hr class="mx-2">
                <span class="mb-2">' . $presc['content'] . '</span>
            </div>';

        return $prescHTML;


        /*
            <div class="form-floating mx-0 mt-2">
                <textarea class="form-control" placeholder="" id="ordo-pharma-presc-textarea-' . $medicEventID . '-' . $presc['ordoPharmaID'] . '-' . $presc['prescPharmaID'] . '" readonly>' . $presc['comment'] . '</textarea>
                <label for="ordo-pharma-presc-textarea-' . $medicEventID . '-' . $presc['ordoPharmaID'] . '-' . $presc['prescPharmaID'] . '">Informations complémentaires</label>
            </div>
        */
    }
}
