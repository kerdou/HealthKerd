<?php

namespace HealthKerd\View\medic\eventsBuilder\presc\labo;

abstract class PrescLaboBuilderFunctionsPool
{
    /** */
    protected function prescStarter(array $presc)
    {
        $prescStarterHTML =
            '<div class="border border-1 rounded-3 p-2 mb-3">
                <div class="me-1"><h5><b>' . $presc['samplingType'] . '</b></h5></div>
                    <hr class="mx-2 mt-2">
                        <h5><u>&Eacute;léments:</u></h5>
                    <ul>';

        return $prescStarterHTML;
    }


    /** */
    protected function prescComment(array $presc)
    {
        $prescCommentHTML =
        '<div class="form-floating mx-0 mt-2">
            <textarea class="form-control" placeholder="" id="presc-labo-textarea">' . $presc['comment'] . '</textarea>
            <label for="presc-labo-textarea">Informations complémentaires</label>
        </div>';

        return $prescCommentHTML;
    }


    /** */
    protected function prescEnd()
    {
        $prescEndHTML =
                '</ul>
            </div>';

        return $prescEndHTML;
    }
}
