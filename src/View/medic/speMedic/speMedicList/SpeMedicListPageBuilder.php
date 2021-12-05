<?php

namespace HealthKerd\View\medic\speMedic\speMedicList;

class SpeMedicListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $speMedicList = array();



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
    public function dataReceiver(array $speMedicList)
    {
        $this->speMedicList = $speMedicList;
        $this->buildOrder();
    }


    /** */
    private function buildOrder()
    {
        $this->builtContentHTML .= '<div class="p-2">';

        $this->builtContentHTML .= '<h3>Spécialités médicales consultées: ' . sizeof($this->speMedicList) . '</h3>';
        $this->builtContentHTML .= '<div class= "d-flex flex-column flex-lg-row flex-wrap">';
        $this->builtContentHTML .= $this->speMedicCardsBuilder($this->speMedicList);
        $this->builtContentHTML .= '</div>';


        $this->builtContentHTML .= '</div>';


        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }


    /** */
    private function speMedicCardsBuilder(array $speMedicList)
    {
        $cardHTMLString = '';
        $cardHTMLArray = array();

        foreach ($speMedicList as $speData) {
            $completeCard = $this->cardTop($speData);
            array_push($cardHTMLArray, $completeCard);
        }

        foreach ($cardHTMLArray as $cardHTML) {
            $cardHTMLString .= $cardHTML;
        }

        return $cardHTMLString;
    }


    /** */
    private function cardTop(array $speMedicList)
    {
        $cardHTML =
        '<a href="index.php?controller=medic&subCtrlr=speMedic&action=dispAllEventsRegrdOneSpe&speMedicID=' . $speMedicList['speMedicID'] . '" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-activity me-2" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z"/>
                    </svg>
                    <h5 class="card-title">' . $speMedicList['speMedicName'] . '</h5>
                </div>
            </div>
        </a>';

        return $cardHTML;
    }
}
