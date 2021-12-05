<?php

namespace HealthKerd\View\medic\doc\oneDoc;

class OneDocPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $docDataArray = array();




    public function __construct()
    {
        parent::__construct();
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


        var_dump($this->docDataArray);


        $this->buildOrder();
    }


    /** */
    private function buildOrder()
    {
        $this->builtContentHTML .= '<h2>' . $this->docDataArray['fullNameSentence'] . '</h2>';
        $this->builtContentHTML .= '<div>';
        $this->builtContentHTML .= $this->speMedicBadgesBuilder($this->docDataArray['speMedicList']);
        $this->builtContentHTML .= '</div>';
        $this->builtContentHTML .= '<a href="index.php?controller=medic&subCtrlr=doc&action=showEventsWithOneDoc&docID=' . $this->docDataArray['docID'] . '" class="btn bg-primary">Voir RDV</a>';
        $this->builtContentHTML .= '<p>Tel: ' . $this->docDataArray['tel'] . '</p>';
        $this->builtContentHTML .= '<p>Adresse mail: ' . $this->docDataArray['mail'] . '</p>';
        $this->builtContentHTML .= '<p>Page web: ' . $this->docDataArray['webPage'] . '</p>';
        $this->builtContentHTML .= '<p>Page DoctoLib: ' . $this->docDataArray['doctolibPage'] . '</p>';
        $this->builtContentHTML .= '<p>Commentaires: ' . $this->docDataArray['comment'] . '</p>';

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }


    /** */
    private function speMedicBadgesBuilder(array $speMedicBadgeList)
    {
        //var_dump($speMedicBadgeList);
        $allBadgesHTMLString = '';
        $allBadgesHTMLArray = array();

        foreach ($speMedicBadgeList as $speValue) {
            $singleBadgeHTML = '<a href="#" class="badge bg-warning me-1 mb-1 text-white">' . $speValue['name'] . '</a>';
            array_push($allBadgesHTMLArray, $singleBadgeHTML);
        }

        foreach ($allBadgesHTMLArray as $cardHTMLPortion) {
            $allBadgesHTMLString .= $cardHTMLPortion;
        }

        return $allBadgesHTMLString;
    }


    /** */
    private function officeCardBuilder(array $officeData)
    {
        $cardHTML =
        '<a href="index.php?controller=medic&subCtrlr=docOffice&action=dispEventsWithOneDocOffice&docOfficeID=' . $officeData['docOfficeID'] . '" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
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
                            <h5 class="card-title">' . $officeData['name'] . '</h5>
                            <p>' . $officeData['cityName'] . '</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>';

        return $cardHTML;
    }
}
