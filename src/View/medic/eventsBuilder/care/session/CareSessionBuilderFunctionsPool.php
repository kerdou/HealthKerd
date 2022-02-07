<?php

namespace HealthKerd\View\medic\eventsBuilder\care\session;

/** Dépot des méthodes de création des blocs de l'accordéon de session de soin
 */
class CareSessionBuilderFunctionsPool
{
    public function __destruct()
    {
    }

    /** Début de la DIV d'accordéon de session de soin
     * @param array $sessionData        Données de la session de soin
     * @param string $medicEventID      ID de l'event
     * @return string                   HTML du début d'accordéon
    */
    protected function careAccordionStart(array $sessionData, string $medicEventID)
    {
        $careAccordionStartHTML =
            '<div id="care-session-accordion-' . $medicEventID . '-' . $sessionData['careSessionID'] . '" class="accordion my-1"> <!-- careSessionID=' . $sessionData['careSessionID'] . ' START OF CARE SESSION ACCORDION -->
                <div class="accordion-item"> <!-- START OF CARE SESSION ITEM -->
                    <h2 class="accordion-header" id="">
                        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#care-session-collapse-' . $medicEventID . '-' . $sessionData['careSessionID'] . '">
                        <b>Réception de soin</b>
                        </button>
                    </h2>
                    <div id="care-session-collapse-' . $medicEventID . '-' . $sessionData['careSessionID'] . '" class="accordion-collapse collapse" data-bs-parent="#care-session-accordion-' . $medicEventID . '-' . $sessionData['careSessionID'] . '"> <!-- START OF CARE SESSION COLLAPSE -->
                        <div class="accordion-body p-2"> <!-- START OF ACCORDION BODY -->
                            <h5>
                                <u>&Eacute;léments</u>
                            </h5>

                            <ul>';

        return $careAccordionStartHTML;
    }

    /** Construction des <LI> des élements de la session de soin
     * @param array $elementsData   Listes des éléments de la session de soin
     * @return string               HTML des <li> concatenées
    */
    protected function careElemBuilder(array $elementsData)
    {
        $careElemBuilderHTML = '';
        $careElemBuilderArray = array();

        foreach ($elementsData as $element) {
            $li = '<li>' . $element['name'] . '</li>';
            array_push($careElemBuilderArray, $li);
        }

        if (sizeof($careElemBuilderArray) > 0) {
            foreach ($careElemBuilderArray as $elementHTMLPortion) {
                $careElemBuilderHTML .= $elementHTMLPortion;
            }
        } else {
            $careElemBuilderHTML = '<li>Aucun élément signalé</li>';
        }

        return $careElemBuilderHTML;
    }

    /** DIV de commentaire de la session de soin
     * @param array $sessionData        Données de la session de soin
     * @param string $medicEventID      ID de l'event
     * @return string                   DIV de commentaire
    */
    protected function careComment(array $sessionData, string $medicEventID)
    {
        $careCommentHTML =
            '<div class="form-floating mx-1 mt-2">
                <textarea class="form-control textarea-ridonli" placeholder="" id="careTextarea-' . $medicEventID . '-' . $sessionData['careSessionID'] . '">' . $sessionData['comment'] .  '</textarea>
                <label for="careTextarea-' . $medicEventID . '-' . $sessionData['careSessionID'] . '">Informations complémentaires</label>
            </div>';

        return $careCommentHTML;
    }

    /** Fin de l'accordéon de session de soin
     * @return string       HTML de la fin de l'accordéon de soin
    */
    protected function careAccordionEnd()
    {
        $careAccordionEndHTML =
                    '</div> <!-- END OF ACCORDION BODY -->
                    </div> <!-- END OF CARE SESSION COLLAPSE -->
                </div> <!-- END OF CARE SESSION ITEM -->
            </div> <!-- END OF CARE SESSION ACCORDION --> ';

        return $careAccordionEndHTML;
    }
}
