<?php

namespace HealthKerd\View\medic\medicTheme\medicThemeList;

/** Construction puis affichage de la page listant tous les thèmes médicaux d'un user
 */
class MedicThemeListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $medicThemeList = array();

    public function __construct()
    {
        parent::__construct();

        $this->pageSettingsList = array(
            "pageTitle" => "Liste des thèmes médicaux"
        );
    }

    public function __destruct()
    {
    }

    /** Recoit les données des thèmes médicaux d'un user puis lance la construction des blocs HTML
     * @param array $medicThemeList     Données des thèmes médicaux
    */
    public function dataReceiver(array $medicThemeList)
    {
        $this->medicThemeList = $medicThemeList;
        $this->buildOrder();
    }

    /** Construction de la liste des thèmes médicaux
    */
    private function buildOrder()
    {
        $this->builtContentHTML .= '<div class="p-2">';

        $this->builtContentHTML .= '<h3>Liste des thèmes médicaux: ' . sizeof($this->medicThemeList) . '</h3>';
        $this->builtContentHTML .= '<div class= "d-flex flex-column flex-lg-row flex-wrap">';
        $this->builtContentHTML .= $this->medicThemeCardsBuilder($this->medicThemeList);
        $this->builtContentHTML .= '</div>';

        $this->builtContentHTML .= '</div>';

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }

    /** Création de l'ensemble des cards de thémes médicaux
     * @param array $medicThemeList     Liste des données de tous les thémes médicaux
     * @return string                   HTML des cards
     */
    private function medicThemeCardsBuilder(array $medicThemeList)
    {
        $cardHTMLString = '';
        $cardHTMLArray = array();

        foreach ($medicThemeList as $themeData) {
            $completeCard = $this->cardBuilder($themeData);
            array_push($cardHTMLArray, $completeCard);
        }

        foreach ($cardHTMLArray as $cardHTML) {
            $cardHTMLString .= $cardHTML;
        }

        return $cardHTMLString;
    }

    /** Création d'une card de théme médical
     * @param array $medicThemeList     Données d'un théme médical
     * @return string                   HTML de la card d'un théme médical
    */
    private function cardBuilder(array $medicThemeList)
    {
        $cardHTML =
        '<a href="index.php?controller=medic&subCtrlr=medicTheme&action=dispAllEventsRegrdOneTheme&medicThemeID=' . $medicThemeList['medicThemeID'] . '" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-activity me-2" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2Z"/>
                    </svg>
                    <h5 class="card-title">' . $medicThemeList['name'] . '</h5>
                </div>
            </div>
        </a>';

        return $cardHTML;
    }
}