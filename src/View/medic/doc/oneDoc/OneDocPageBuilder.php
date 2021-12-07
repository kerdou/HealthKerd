<?php

namespace HealthKerd\View\medic\doc\oneDoc;

class OneDocPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $builtContentArray = array();
    private array $tempContent = array();
    private array $docDataArray = array();

    public function __construct()
    {
        parent::__construct();

        $this->tempContent['telPortion'] = '';
        $this->tempContent['MailPortion'] = '';
        $this->tempContent['phoneAndMailContent'] = '';

        $this->tempContent['persoWebPagePortion'] = '';
        $this->tempContent['doctoLibPagePortion'] = '';
        $this->tempContent['websitesContent'] = '';

        $this->tempContent['contactContent'] = '';

        /*
        $this->pageSettingsList = array(
            "pageTitle" => "Page de connexion"
        );
        */
    }


    public function __destruct()
    {
    }


    /** PLOP */
    public function dataReceiver(array $docDataArray)
    {
        $this->docDataArray = $docDataArray;
        $this->tempContentManagement();
        $this->buildOrder();
    }


    /** */
    private function buildOrder()
    {
        $this->builtContentArray['generalDivStart'] = '<div class="p-2"> <!-- GENERAL DIV START -->';

        $this->builtContentArray['docName'] = $this->docNameBuilder();
        $this->builtContentArray['speMedicList'] = $this->speMedicBadgesBuilder($this->docDataArray['speMedicList']);
        $this->builtContentArray['contactContent'] = $this->tempContent['contactContent'];
        $this->builtContentArray['comment'] = $this->commentBuilder();

        $this->builtContentArray['firstBR'] = '<br>';
        $this->builtContentArray['medicEvents'] = $this->medicEventsReport();
        $this->builtContentArray['secondBR'] = '<br>';

        $this->builtContentArray['docOfficeStart'] =    '<h3>Lieux d\'exercice</h3>
                                                        <div class= "d-flex flex-column flex-lg-row flex-wrap"> <!-- DOCOFFICE START -->';
        $this->builtContentArray['docOfficeCards'] = $this->officeCardBuilder($this->docDataArray['docOfficeList']);
        $this->builtContentArray['docOfficeEnd'] = '</div>  <!-- DOCOFFICE END -->';


        $this->builtContentArray['generalDivEnd'] = '</div> <!-- GENERAL DIV END -->';

        foreach ($this->builtContentArray as $portions) {
            $this->builtContentHTML .= $portions;
        }


        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }


    /** */
    private function tempContentManagement()
    {
        if (strlen($this->docDataArray['tel'] > 0)) {
            $this->tempContent['telPortion'] = $this->telPortionBuilder();
        } else {
            $this->tempContent['telPortion'] = '';
        }


        if (strlen($this->docDataArray['mail'] > 0)) {
            $this->tempContent['MailPortion'] = $this->mailPortionBuilder();
        } else {
            $this->tempContent['MailPortion'] = '';
        }


        if (
            strlen($this->tempContent['telPortion']) > 0 ||
            strlen($this->tempContent['MailPortion']) > 0
        ) {
            $this->tempContent['phoneAndMailContent'] = '<div class="d-flex flex-column flex-fill border rounded-3 my-2 me-lg-2 col-12 col-lg-4 p-2"> <!-- PHONE AND MAIL START -->';
            $this->tempContent['phoneAndMailContent'] .= $this->tempContent['telPortion'];
            $this->tempContent['phoneAndMailContent'] .= $this->tempContent['MailPortion'];
            $this->tempContent['phoneAndMailContent'] .= '</div> <!-- PHONE AND MAIL END -->';
        }


        if (strlen($this->docDataArray['webPage'] > 0)) {
            $this->tempContent['persoWebPagePortion'] = $this->persoWebPagePortion();
        }


        if (strlen($this->docDataArray['doctolibPage'] > 0)) {
            $this->tempContent['doctoLibPagePortion'] = $this->doctoLibPagePortion();
        }


        if (
            strlen($this->tempContent['persoWebPagePortion']) > 0 ||
            strlen($this->tempContent['doctoLibPagePortion']) > 0
        ) {
            $this->tempContent['websitesContent'] = '<div class="d-flex flex-column flex-fill border rounded-3 my-2 ms-lg-2 col-12 col-lg-4 p-2"> <!-- WEBSITES START -->';
            $this->tempContent['websitesContent'] .= $this->tempContent['persoWebPagePortion'];
            $this->tempContent['websitesContent'] .= $this->tempContent['doctoLibPagePortion'];
            $this->tempContent['websitesContent'] .= '</div> <!-- WEBSITES END -->';
        }


        if (
            (strlen($this->tempContent['phoneAndMailContent']) > 0) ||
            (strlen($this->tempContent['websitesContent']) > 0)
        ) {
            $this->tempContent['contactContent'] = '<div class="d-flex flex-column flex-lg-row"> <!-- CONTACTS START -->';
            $this->tempContent['contactContent'] .= $this->tempContent['phoneAndMailContent'];
            $this->tempContent['contactContent'] .= $this->tempContent['websitesContent'];
            $this->tempContent['contactContent'] .= '</div> <!-- CONTACTS END -->';
        }
    }


    /** */
    private function docNameBuilder()
    {
        $docNameTopBuilderHTML =
            '<div class="d-flex flex-row"> <!-- DOC NAME START -->
                <h2>' . $this->docDataArray['fullNameSentence'] .  '</h2>';


        $docNameMidBuilderHTML = '';

        if ($this->docDataArray['isLocked'] == false) {
            $docNameMidBuilderHTML =
                '<a href="" class="d-flex flex-column justify-content-center ms-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                    </svg>
                </a>';
        }

        $docNameBotBuilderHTML = '</div> <!-- DOC NAME END -->';

        return $docNameTopBuilderHTML . $docNameMidBuilderHTML . $docNameBotBuilderHTML;
    }


    /** */
    private function speMedicBadgesBuilder(array $speMedicBadgeList)
    {
        //var_dump($speMedicBadgeList);
        $speMedicDivStart = '<div class="d-flex flex-row flex-wrap"> <!-- SPE MEDIC START -->';
        $allBadgesHTMLString = '';
        $speMedicDivEnd = '</div> <!-- SPE MEDIC END -->';
        $allBadgesHTMLArray = array();

        foreach ($speMedicBadgeList as $speValue) {
            $singleBadgeHTML = '<a href="#" class="badge bg-warning me-1 mb-1 text-white">' . $speValue['name'] . '</a>';
            array_push($allBadgesHTMLArray, $singleBadgeHTML);
        }

        foreach ($allBadgesHTMLArray as $cardHTMLPortion) {
            $allBadgesHTMLString .= $cardHTMLPortion;
        }

        return $speMedicDivStart . $allBadgesHTMLString . $speMedicDivEnd;
    }


    /** */
    private function telPortionBuilder()
    {
        $telPortionBuilderHTML =
            '<a href="tel:' . $this->docDataArray['tel'] . '" class="my-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-telephone me-2" viewBox="0 0 16 16">
                    <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                </svg>
                ' . $this->docDataArray['tel'] . '
            </a>';

        return $telPortionBuilderHTML;
    }


    /** */
    private function mailPortionBuilder()
    {
        $mailPortionBuilderHTML =
            '<a href="mailto:' . $this->docDataArray['mail'] . '" class="my-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-envelope me-2" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                </svg>
                ' . $this->docDataArray['mail'] . '
            </a>';

        return $mailPortionBuilderHTML;
    }


    /** */
    private function persoWebPagePortion()
    {
        $persoWebPagePortionHTML =
            '<a href="' . $this->docDataArray['webPage'] . '" target="_blank" title="addressePerso.com" class="my-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
                    <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                </svg>
                Page personnelle
            </a>';

        return $persoWebPagePortionHTML;
    }


    /** */
    private function doctoLibPagePortion()
    {
        $doctoLibPagePortionHTML =
            '<a href="' . $this->docDataArray['doctolibPage'] . '" target="_blank" title="adresse.doctolib.com" class="my-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
                    <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                </svg>
                Page DoctoLib
            </a>
            ';

        return $doctoLibPagePortionHTML;
    }


    /** */
    private function commentBuilder()
    {
        $commentBuilderHTML =
            '<div class="form-floating mt-2"> <!-- COMMENT START -->
                <textarea class="form-control textarea-ridonli" placeholder="" id="event-floating-Textarea-1" readonly>' . $this->docDataArray['comment'] . '</textarea>
                <label for="event-floating-Textarea-1">Informations complémentaires</label>
            </div> <!-- COMMENT END -->';

        return $commentBuilderHTML;
    }


    /** */
    private function medicEventsReport()
    {
        $medicEventsReportHTML =
            '<h3>Rendez-vous médicaux</h3>

            <div class="d-flex flex-column flex-lg-row flex-wrap"> <!-- APPOINT START -->

                <div class="card col-12 col-lg-3 col-xxl-2 rounded-3 mb-3 me-lg-3"> <!-- NUMBERS START -->
                    <div class="card-body">
                        <h5 class="card-title">Chiffres</h5>
                        <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex flex-row">
                            <div style="width: 5rem;" class="me-2">Passés:</div>
                            <div>' . $this->docDataArray['medicEvent']['qty']['past'] . '</div>
                        </li>
                        <li class="list-group-item d-flex flex-row">
                            <div style="width: 5rem;" class="me-2">&Agrave; venir:</div>
                            <div>' . $this->docDataArray['medicEvent']['qty']['coming'] . '</div>
                        </li>
                        <li class="list-group-item d-flex flex-row">
                            <div style="width: 5rem;" class="me-2">Total:</div>
                            <div>' . $this->docDataArray['medicEvent']['qty']['total'] . '</div>
                        </li>
                        </ul>
                    </div>
                </div> <!-- NUMBERS END -->

                <div class="card col-12 col-lg-8 col-xl-7 col-xxl-6 flex-lg-fill rounded-3 mb-3 me-0 me-xxl-3"> <!-- DATES START -->
                    <div class="card-body">
                        <h5 class="card-title">Dates</h5>
                        <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex flex-row">
                            <div style="width: 5rem;" class="me-2">Premier:</div>
                            <div>' . $this->docDataArray['medicEvent']['dates']['first'] . '</div>
                        </li>
                        <li class="list-group-item d-flex flex-row">
                            <div style="width: 5rem;" class="me-2">Dernier:</div>
                            <div>' . $this->docDataArray['medicEvent']['dates']['last'] . '</div>
                        </li>
                        <li class="list-group-item d-flex flex-row">
                            <div style="width: 5rem;" class="me-2">Prochain:</div>
                            <div>' . $this->docDataArray['medicEvent']['dates']['next'] . '</div>
                        </li>
                        </ul>
                    </div>
                </div> <!-- DATES END -->
                <a href="index.php?controller=medic&subCtrlr=doc&action=showEventsWithOneDoc&docID=' . $this->docDataArray['docID'] . '" class="col-12 flex-fill col-xl-2 col-xxl-3 mb-3 rounded-3">
                <div class="card h-100 ">
                    <div class="card-body">
                        <h5 class="card-title">Afficher les rendez-vous</h5>
                    </div>
                </div>
            </a>

        </div>  <!-- APPOINT END -->';

        return $medicEventsReportHTML;
    }


    /** */
    private function officeCardBuilder(array $officeData)
    {
        $allCardsHTML = '';
        $allCardsArray = array();

        foreach ($officeData as $value) {
            $cardSingleHTML =
            '<a href="index.php?controller=medic&subCtrlr=docOffice&action=dispEventsWithOneDocOffice&docOfficeID=' . $value['docOfficeID'] . '" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-building me-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"/>
                                    <path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z"/>
                                </svg>
                            </div>
                            <div class="d-flex flex-column">
                                <h5 class="card-title">' . $value['name'] . '</h5>
                                <p>' . $value['cityName'] . '</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>';

            array_push($allCardsArray, $cardSingleHTML);
        }


        foreach ($allCardsArray as $HTMLPortion) {
            $allCardsHTML .= $HTMLPortion;
        }

        return $allCardsHTML;
    }
}
