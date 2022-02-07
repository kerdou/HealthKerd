<?php

namespace HealthKerd\View\medic\eventsBuilder\diag\ordo\labo;

/** Dépot des méthodes de création de blocs pour les prescription de prélévement en laboratoire médical
 */
abstract class PrescLaboBuilderFunctionsPool
{
    public function __destruct()
    {
    }

    /** Début de la <div> de prescription de prélévement en laboratoire médical
     * @param string $samplingType      Type de prélévement
     * @return string                   HTML du début de la <div>
    */
    protected function prescStarter(string $samplingType)
    {
        $prescStarterHTML =
            '<div class="border border-1 rounded-3 p-2 my-1">
                <div class="me-1"><h5><b>' . $samplingType . '</b></h5></div>
                    <hr class="mx-2 mt-2">
                        <h5><u>&Eacute;léments:</u></h5>
                    <ul>';

        return $prescStarterHTML;
    }

    /** Fin de la <div> de prescription de prélévement en laboratoire médical
     * @return string                   HTML de la fin de la <div>
    */
    protected function prescEnd()
    {
        $prescEndHTML =
                '</ul>
            </div>';

        return $prescEndHTML;
    }
}
