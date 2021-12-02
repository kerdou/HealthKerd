<?php

namespace HealthKerd\View\medic\doc\oneDoc;

class OneDocPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $docDataArray = array();
    private array $speMedicBadgeList = array();



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
    public function dataReceiver(array $docAndSpeDatas)
    {
        $this->docDataArray = $docAndSpeDatas['doc'];
        $this->speMedicBadgeList = $docAndSpeDatas['speMedic'];
        $this->buildOrder();
    }


    /** */
    private function buildOrder()
    {
        $this->builtContentHTML .= '<h2>' . $this->docDataArray['firstName'] . ' ' . $this->docDataArray['lastName'] . '</h2>';
        $this->builtContentHTML .= '<a href="index.php?controller=medic&subCtrlr=doc&action=showEventsWithOneDoc&docID=' . $this->docDataArray['docID'] . '" class="btn bg-primary">Voir RDV</a>';


        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }


    /** */
    private function docCardsBuilder(array $docList)
    {
        $cardHTMLString = '';
        $cardHTMLArray = array();

        foreach ($docList as $docData) {
            $top = $this->cardTop($docData);
            $cardBadges = $this->cardSpeMedicBadgesBuilder($docData);
            $bottom = $this->cardBottom();

            $completeCard = $top . $cardBadges . $bottom;
            array_push($cardHTMLArray, $completeCard);
        }

        foreach ($cardHTMLArray as $cardHTML) {
            $cardHTMLString .= $cardHTML;
        }

        return $cardHTMLString;
    }


    /** */
    private function cardTop(array $docData)
    {
        $cardTopHTML =
        '<a href="index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=' . $docData['docID'] . '" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
            <div class="card h-100">
                <div class="card-body">
                <h5 class="card-title">' . $docData['fullNameSentence'] . '</h5>';

        return $cardTopHTML;
    }


    /** */
    private function cardSpeMedicBadgesBuilder(array $docData)
    {
        $allBadgesHTMLString = '';
        $allBadgesHTMLArray = array();

        foreach ($docData['speMedicList'] as $speValue) {
            $singleBadgeHTML = '<div class="badge bg-warning me-1 mb-1 text-white">' . $speValue['name'] . '</div>';
            array_push($allBadgesHTMLArray, $singleBadgeHTML);
        }

        foreach ($allBadgesHTMLArray as $cardHTMLPortion) {
            $allBadgesHTMLString .= $cardHTMLPortion;
        }

        return $allBadgesHTMLString;
    }


    /** */
    private function cardBottom()
    {
        $cardBottomHTML =
                    '</div>
                </div>
            </a>';

        return $cardBottomHTML;
    }


    /** */
    private function addDocButton()
    {
        $addDocButtonHTML =
            '<a href="#" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-row">
                        <div class="me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </div>
                        <h5 class="card-title">Ajouter un professionnel de santé</h5>
                    </div>
                </div>
            </a>';

        return $addDocButtonHTML;
    }


    /** */
    private function speMedicBadgesBuilder(array $speMedicBadgeList)
    {
        //var_dump($speMedicBadgeList);
        $allBadgesHTMLString = '';
        $allBadgesHTMLArray = array();

        foreach ($speMedicBadgeList as $speValue) {
            $singleBadgeHTML = '<a href="#" class="badge bg-warning me-1 mb-1 text-white">' . $speValue['speMedicName'] . '</a>';
            array_push($allBadgesHTMLArray, $singleBadgeHTML);
        }

        foreach ($allBadgesHTMLArray as $cardHTMLPortion) {
            $allBadgesHTMLString .= $cardHTMLPortion;
        }

        return $allBadgesHTMLString;
    }
}
