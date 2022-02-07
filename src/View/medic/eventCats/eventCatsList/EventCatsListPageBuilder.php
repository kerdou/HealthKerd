<?php

namespace HealthKerd\View\medic\eventCats\eventCatsList;

/** Construction et affichage de la page listant les catégories d'événements
 */
class EventCatsListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $eventCatsList = array();

    public function __construct()
    {
        parent::__construct();

        $this->pageSettingsList = array(
            "pageTitle" => "Catégories d'événements médicaux"
        );
    }

    public function __destruct()
    {
    }

    /** Recoit les données puis lance la création de la liste des catégories d'événements
     * @param array $eventCatsList     Données des catégories d'événements
    */
    public function dataReceiver(array $eventCatsList)
    {
        $this->eventCatsList = $eventCatsList;
        $this->buildOrder();
    }

    /** Construction de la liste des catégories d'événements
     * Puis configuration de la page et affichage du contenu
     */
    private function buildOrder()
    {
        $this->builtContentHTML .= '<div class="p-2">';

        $this->builtContentHTML .= '<h3>Catégories d\'événements médicaux: ' . sizeof($this->eventCatsList) . '</h3>';
        $this->builtContentHTML .= '<div class= "d-flex flex-column flex-lg-row flex-wrap">';
        $this->builtContentHTML .= $this->eventCatCardsBuilder($this->eventCatsList);
        $this->builtContentHTML .= '</div>';

        $this->builtContentHTML .= '</div>';

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }

    /** Création de la liste des cards des catégories d'événements
     * @param array $eventCatsList      Données des catégories d'événements
     * @return string                   HTML de la liste des catégories d'événements
    */
    private function eventCatCardsBuilder(array $eventCatsList)
    {
        $cardHTMLString = '';
        $cardHTMLArray = array();

        foreach ($eventCatsList as $speData) {
            $completeCard = $this->cardBuilder($speData);
            array_push($cardHTMLArray, $completeCard);
        }

        foreach ($cardHTMLArray as $cardHTML) {
            $cardHTMLString .= $cardHTML;
        }

        return $cardHTMLString;
    }

    /** Création de chaque card de catégorie d'événements
     * @param array $eventCatsList  Données d'une seule catégorie d'événements
     * @return string               HTML d'une card de catégories d'événements
     */
    private function cardBuilder(array $eventCatsList)
    {
        $cardHTML =
        '<a href="index.php?controller=medic&subCtrlr=eventCat&action=dispAllEventsRegrdOneCat&medicEventCatID=' . $eventCatsList['medicEventCatID'] . '" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-row">
                    <h5 class="card-title">' . $eventCatsList['name'] . '</h5>
                </div>
            </div>
        </a>';

        return $cardHTML;
    }
}
