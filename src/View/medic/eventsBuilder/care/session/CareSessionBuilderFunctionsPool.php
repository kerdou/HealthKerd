<?php

namespace HealthKerd\View\medic\eventsBuilder\care\session;

class CareSessionBuilderFunctionsPool
{
    public function __destruct()
    {
    }


    /** */
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


    /** */
    protected function careElemBuilder(array $sessionData)
    {
        $careElemBuilderHTML = '';
        $careElemBuilderArray = array();

        foreach ($sessionData['elements'] as $element) {
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


    /** */
    protected function careComment(array $sessionData, string $medicEventID)
    {
        $careCommentHTML =
            '<div class="form-floating mx-1 mt-2">
                <textarea class="form-control textarea-ridonli" placeholder="" id="careTextarea-' . $medicEventID . '-' . $sessionData['careSessionID'] . '">' . $sessionData['comment'] .  '</textarea>
                <label for="careTextarea-' . $medicEventID . '-' . $sessionData['careSessionID'] . '">Informations complémentaires</label>
            </div>';

        return $careCommentHTML;
    }


    /** */
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
