<?php

namespace HealthKerd\View\medic\doc\docList;

/** Assemblage de la liste des docteurs avant affichage
 */
class DocListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $docList = array();
    private array $speMedicBadgeList = array();

    public function __construct()
    {
        parent::__construct();

        $this->pageSettingsList = array(
            "pageTitle" => "Professionnels de santé consultés"
        );
    }

    public function __destruct()
    {
    }

    /** Recoit les données des docteurs et les datas des badges de spécialités médicales
     * @param array $docList            Données des docteurs
     * @param array $speMedicBadgeList  Données des badges de spécialités médicales
     */
    public function dataReceiver(array $docList, array $speMedicBadgeList)
    {
        $this->docList = $docList;
        $this->speMedicBadgeList = $speMedicBadgeList;
        $this->buildOrder();
    }

    /** Assemblage des blocs HTML
     * Puis configuration de la page et affichage du contenu
     */
    private function buildOrder()
    {
        $this->builtContentHTML .= '<div class="p-2">'; // début de la DIV de contenu

        $this->builtContentHTML .= '<h3>Professionnels de santé consultés: ' . sizeof($this->docList) . '</h3>';
        $this->builtContentHTML .= '<div class= "d-flex flex-column flex-lg-row flex-wrap">'; // début de la DIV des docteurs
        $this->builtContentHTML .= $this->docCardsBuilder($this->docList); // cards des docteurs
        $this->builtContentHTML .= $this->addDocButton(); // bouton d'ajout de docteur
        $this->builtContentHTML .= '</div>'; // fin de la DIV des docteurs

        $this->builtContentHTML .= '<h3 class="d-flex flew-wrap mt-3">Spécialités médicales consultées: ' . sizeof($this->speMedicBadgeList) . '</h3>';
        $this->builtContentHTML .= '<div>'; // début de la DIV des badges de spécialités médicales
        $this->builtContentHTML .= $this->speMedicBadgesBuilder($this->speMedicBadgeList); // badges de spécialités médicales
        $this->builtContentHTML .= '</div>'; // fin de la DIV des badges de spécialités médicales

        $this->builtContentHTML .= '</div>'; // fin de la DIV de contenu


        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }

    /** Construction des cards des docteurs
     * @param array $docList    Données des docteurs
     * @return string           HTML de l'ensemble des cards des docteurs
    */
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

    /** Partie haute d'une card de docteur
     * @param array $docData    Données d'un seul docteur
     * @return string           HTML de la partie haute d'une card de docteur
    */
    private function cardTop(array $docData)
    {
        $cardTopHTML =
        '<a href="index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=' . $docData['docID'] . '" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
            <div class="card h-100">
                <div class="card-body">
                <h5 class="card-title">' . $docData['fullNameSentence'] . '</h5>';

        return $cardTopHTML;
    }

    /** Badges de spé médicale au sein d'une card de docteur
     * @param array $docData    Données d'un seul docteur
     * @return string           HTML des badges de spé medic d'un docteur
    */
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

    /** Partie basse d'une card de docteur
     * @return string   HTML de la partie basse d'une card de docteur
    */
    private function cardBottom()
    {
        $cardBottomHTML =
                    '</div>
                </div>
            </a>';

        return $cardBottomHTML;
    }

    /** Bouton d'ajout de docteur
     * @return string   HTML de bouton d'ajout de docteur
     */
    private function addDocButton()
    {
        $addDocButtonHTML =
            '<a href="index.php?controller=medic&subCtrlr=doc&action=showDocAddForm" class="col-12 col-lg-4 flex-fill rounded-3 mb-3 me-lg-3">
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

    /** Création de la liste des badges de spé medic
     * @param array $speMedicBadgeList  Liste de spécialités médicales
     * @return string                   HTML des badges de spé medic
    */
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
