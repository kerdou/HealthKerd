<?php

namespace HealthKerd\View\medic\docOffice\docOfficeList;

/** Construction et affichage de la page listant les cabinets médicaux
 */
class DocOfficeListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $docOfficeList = array();

    public function __construct()
    {
        parent::__construct();

        $this->pageSettingsList = array(
            "pageTitle" => "Liste des cabinets médicaux consultés"
        );
    }

    public function __destruct()
    {
    }

    /** Recoit les données puis lance la création de la liste des cabinets médicaux
     * @param array $docOfficeList     Données des cabinets médicaux
    */
    public function dataReceiver(array $docOfficeList)
    {
        $this->docOfficeList = $docOfficeList;
        $this->buildOrder();
    }

    /** Construction de la liste des cabinets médicaux
     * Puis configuration de la page et affichage du contenu
     */
    private function buildOrder()
    {
        $this->builtContentHTML .= '<div class="p-2">';

        $this->builtContentHTML .= '<h3>Liste des cabinets médicaux: ' . sizeof($this->docOfficeList) . '</h3>';
        $this->builtContentHTML .= '<div class= "d-flex flex-column flex-lg-row flex-wrap">';
        $this->builtContentHTML .= $this->docOfficeCardsBuilder($this->docOfficeList);
        $this->builtContentHTML .= '</div>';

        $this->builtContentHTML .= '</div>';

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }

    /** Création de la liste des cards des cabinets médicaux
     * @param array $docOfficeList      Données des cabinets médicaux
     * @return string                   HTML de la liste des cabinets médicaux
    */
    private function docOfficeCardsBuilder(array $docOfficeList)
    {
        $cardHTMLString = '';
        $cardHTMLArray = array();

        foreach ($docOfficeList as $officeData) {
            $completeCard = $this->cardBuilder($officeData);
            array_push($cardHTMLArray, $completeCard);
        }

        foreach ($cardHTMLArray as $cardHTML) {
            $cardHTMLString .= $cardHTML;
        }

        return $cardHTMLString;
    }

    /** Création de chaque card de cabinet médical
     * @param array $officeData     Données d'un seul cabinet médical
     * @return string               HTML d'une card de cabinet médical
     */
    private function cardBuilder(array $officeData)
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
