<?php

namespace HealthKerd\View\medic\eventsBuilder;

abstract class EventsBuilderFunctionPool
{

    /** */
    protected function eventAccordStart(array $value)
    {
        $eventAccordStartHTML =
            '<div id="event-accordion-' . $value['medicEventID'] . '" class="accordion border border-2 rounded-3 my-4" >  <!-- medicEventID = ' . $value['medicEventID'] . ' START OF EVENT ACCORDION -->
                <div class="accordion-item"> <!-- START OF EVENT ACCORDION ITEM -->';

        return $eventAccordStartHTML;
    }


    /** */
    protected function eventAccordHeaderStart()
    {
        $eventAccordHeaderStartHTML =
            '<h2 class="accordion-header rounded-3 p-1 fs-6" id=""> <!-- START OF EVENT ACCORDION HEADER -->
                <div class="rounded-3">';

        return $eventAccordHeaderStartHTML;
    }


    /** */
    protected function docSpeMedicBadges(array $speMedicList)
    {
        $allBadgesHTML = '';

        foreach ($speMedicList as $spe) {
            $singleBadgeHTML = '<a href="#" class="badge mb-1 me-1 bg-warning text-white">' . $spe['name'] . '</a>';
            $allBadgesHTML .= $singleBadgeHTML;
        }

        return $allBadgesHTML;
    }


    /** */
    protected function attendedPro(array $pro, string $docSpeMedicBadges)
    {
        $attendedProHTML =
            '<div class="event-pro d-flex justify-content-start p-1"> <!-- START OF PRO -->
                <div class="me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                </div>
                <a href="#" class="me-2 link-unstyled">' . $pro['fullNameSentence'] . '</a>
                <div>' . $docSpeMedicBadges . '</div>
            </div> <!-- END OF PRO -->';

        return $attendedProHTML;
    }


    /** */
    protected function replacedPro(array $pro, string $docSpeMedicBadges)
    {
        $replacedProHTML =
            '<div class="event-replaced-pro d-flex justify-content-start p-1"> <!-- START OF REPLACED PRO -->
                <div class="me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                </div>
                <div class="me-1">Remplaçant de</div>
                <a href="#" class="me-2">' . $pro['fullNameSentence'] . '</a>
                <div>' . $docSpeMedicBadges . '</div>
            </div> <!-- END OF REPLACED PRO -->';

        return $replacedProHTML;
    }


    /** */
    protected function eventDateTime(array $time)
    {
        $eventDateTimeHTML =
            '<hr class="mx-4 my-2">
            <div class="event-date-time d-flex justify-content-start p-1"> <!-- START OF DATE TIME -->
                <div class="me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                        <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                        <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                </div>
                <div class="pe-1">' . $time['frenchDate'] . ' à ' . $time['time'] . '</div>
            </div> <!-- END OF DATE TIME -->';

        return $eventDateTimeHTML;
    }


    /** */
    protected function eventDocOffice(array $docOffice)
    {
        $docOfficeHTML =
            '<div class="event-doc-office d-flex justify-content-start  p-1"> <!-- START OF DOC OFFICE -->
                <div class="me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"/>
                        <path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z"/>
                    </svg>
                </div>
                <a href="#" class="pe-1">' . $docOffice['name'] . '</a>
            </div> <!-- END OF DOC OFFICE -->
            <hr class="mx-4 my-2">';

        return $docOfficeHTML;
    }


    /** */
    protected function eventSubject(string $eventTitle)
    {
        $eventTitleHTML =
            '<div class="event-subject d-flex justify-content-start p-1"> <!-- START OF SUBJECT -->
            <div class="me-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bullseye" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M8 13A5 5 0 1 1 8 3a5 5 0 0 1 0 10zm0 1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                    <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                    <path d="M9.5 8a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                </svg>
            </div>
            <div class="pe-1">' . $eventTitle . ' </div>
            </div> <!-- END OF SUBJECT -->';

        return $eventTitleHTML;
    }


    /** */
    protected function eventCategory(string $eventCategoryName)
    {
        $eventCategoryHTML =
            '<hr class="mx-4 my-2">

            <div class="event-category d-flex justify-content-start p-1"> <!-- START OF CATEGORY -->
                <div class="me-2">Catégorie:</div>
                <a href="#"  class="badge bg-success text-white">' . $eventCategoryName . '</a>
            </div> <!-- END OF CATEGORY -->';

        return $eventCategoryHTML;
    }


    /** */
    protected function medicThemeBadges(array $eventMedicThemesList)
    {
        $allBadgesHTML = '';

        foreach ($eventMedicThemesList as $theme) {
            $singleBadgeHTML = '<a href="#" class="badge mb-1 me-1 bg-primary text-white">' . $theme['medicThemeName'] . '</a>';
            $allBadgesHTML .= $singleBadgeHTML;
        }

        return $allBadgesHTML;
    }


    /** */
    protected function eventMedicTheme($medicThemeBadges)
    {
        $medicThemesHTML =
            '<div class="event-theme d-flex justify-content-start p-1"> <!-- START OF THEME -->
                <div class="me-2">Thème(s):</div>
                <div>' . $medicThemeBadges . '</div>
            </div> <!-- END OF THEME -->

            <hr class="mx-4 my-2">';

        return $medicThemesHTML;
    }


    /** */
    protected function eventContentButton($value)
    {
        $eventContentButtonHTML =
        '<button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#event-content-accord-' . $value['medicEventID'] . '">
            <b>Contenu de l\'évènement</b>
        </button>';

        return $eventContentButtonHTML;
    }


    /** */
    protected function eventAccordHeaderEnd()
    {
        $eventAccordHeaderEndHTML =
                '</div>
            </h2> <!-- END OF EVENT ACCORDION HEADER -->';

        return $eventAccordHeaderEndHTML;
    }


    /** */
    protected function eventAccordBodyStart($value)
    {
        $eventAccordBodyStartHTML =
            '<div id="event-content-accord-' . $value['medicEventID'] . '" class="accordion-collapse collapse"> <!-- START OF EVENT CONTENT ACCORDION COLLAPSE -->
                <div class="accordion-body p-2"> <!-- START OF EVENT CONTENT ACCORDION BODY -->';

        return $eventAccordBodyStartHTML;
    }


    /** */
    protected function eventFullAddrAccordStart(array $value)
    {
        $eventFullAddrAccordStartHTML = '';

        $eventFullAddrAccordStartHTML =
            '<div id="event-address-accordion-' . $value['medicEventID'] . '" class="accordion"> <!-- START OF COMPLETE ADDRESS ACCORDION -->
            <div class="accordion-item"> <!-- START OF COMPLETE ADDRESS ACCORDION ITEM -->
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed"  data-bs-toggle="collapse" data-bs-target="#event-addresse-collapse-' . $value['medicEventID'] . '">
                        <b>Adresse complète</b>
                    </button>
                </h2>
                <div id="event-addresse-collapse-' . $value['medicEventID'] . '" class="accordion-collapse collapse" data-bs-parent="#event-address-accordion-' . $value['medicEventID'] . '"> <!-- START OF COMPLETE ADDRESS ACCORDION COLLAPSE -->
                    <div class="accordion-body">';

        return $eventFullAddrAccordStartHTML;
    }


    /** */
    protected function eventFullAddrAccordContent(array $addrData)
    {
        $addressHTML = '';

        $addressHTML .= '<p class="mb-0">' . $addrData['name'] . '</p>';
        $addressHTML .= '<p class="mb-0">' . $addrData['addr1'] . '</p>';

        if (strlen($addrData['addr2']) > 0) {
            $addressHTML .= '<p class="mb-0">' . $addrData['addr2'] . '</p>';
        }

        $addressHTML .= '<p class="mb-0">' . $addrData['postCode'] . '</p>';
        $addressHTML .= '<p class="mb-0"> ' . $addrData['cityName'] . '</p>';

        return $addressHTML;
    }


    /** */
    protected function eventFullAddrAccordEnd()
    {
        $eventFullAddrAccordEndHTML =
                    '</div>
                    </div>  <!-- END OF COMPLETE ADDRESS ACCORDION COLLAPSE -->
                </div> <!-- END OF COMPLETE ADDRESS ACCORDION ITEM -->
            </div> <!-- END OF COMPLETE ADDRESS ACCORDION -->';

        return $eventFullAddrAccordEndHTML;
    }


    /** */
    protected function eventAccordComment($value)
    {
        $eventAccordCommentHTML =
        '<div class="form-floating mx-1 mt-2">
            <textarea class="form-control" placeholder="" id="event-floating-Textarea-' . $value['medicEventID'] . '" readonly>' . $value['comment'] . '</textarea>
            <label for="event-floating-Textarea-' . $value['medicEventID'] . '">Informations complémentaires sur l\'évènement</label>
        </div>';

        return $eventAccordCommentHTML;
    }


    /** */
    protected function eventAccordBodyEnd()
    {
        $eventAccordBodyEndHTML =
                '</div>  <!-- END OF EVENT CONTENT ACCORDION -->
            </div> <!-- END OF EVENT CONTENT ACCORDION COLLAPSE -->';

        return $eventAccordBodyEndHTML;
    }


    /** */
    protected function eventAccordEnd()
    {
        $eventAccordEndHTML =
                '</div> <!-- END OF EVENT ACCORDION ITEM -->
            </div> <!-- END OF EVENT ACCORDION -->';

        return $eventAccordEndHTML;
    }
}
