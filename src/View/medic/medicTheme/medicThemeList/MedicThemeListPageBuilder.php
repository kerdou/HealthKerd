<?php

namespace HealthKerd\View\medic\medicTheme\medicThemeList;

/** Construction puis affichage de la page listant tous les thèmes médicaux d'un user
 */
class MedicThemeListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $medicThemeList = array();
    private string $medicThemeCardListHTML = '';

    public function __construct()
    {
        parent::__construct();
        $this->pageElementsSettingsList();
        $this->pageElementsStringReplace();
    }

    public function __destruct()
    {
    }

    /** Liste des paramétres de la page, sans le contenu
     */
    private function pageElementsSettingsList(): void
    {
        $this->pageSettingsList = array(
            'headContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/head.html'),
            "pageTitle" => 'Liste des thèmes médicaux',
            'headerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/header.html'),
            'mainContainer' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/mainContainer.html'),
            'sidebarMenuUlContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/sidebarMenuUlContent.html'),
            'userFullName' => $_SESSION['firstName'] . ' ' . $_SESSION['lastName'],
            'scrollUpButton' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/scrollUpArrow.html'),
            'footerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/footer.html'),
            'HTMLBottomDeclarations' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/HTMLBottomDeclarations.html')
        );
    }

    /** Application de tous les paramétres listés dans $pageSettingsList
     */
    private function pageElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{headContent}', $this->pageSettingsList['headContent'], $this->pageContent);
        $this->pageContent = str_replace('{pageTitle}', $this->pageSettingsList['pageTitle'], $this->pageContent);
        $this->pageContent = str_replace('{headerContent}', $this->pageSettingsList['headerContent'], $this->pageContent);
        $this->pageContent = str_replace('{mainContainer}', $this->pageSettingsList['mainContainer'], $this->pageContent);
        $this->pageContent = str_replace('{sidebarMenuUlContent}', $this->pageSettingsList['sidebarMenuUlContent'], $this->pageContent);
        $this->pageContent = str_replace('{userFullName}', $this->pageSettingsList['userFullName'], $this->pageContent);
        $this->pageContent = str_replace('{scrollUpButton}', $this->pageSettingsList['scrollUpButton'], $this->pageContent);
        $this->pageContent = str_replace('{footerContent}', $this->pageSettingsList['footerContent'], $this->pageContent);
        $this->pageContent = str_replace('{HTMLBottomDeclarations}', $this->pageSettingsList['HTMLBottomDeclarations'], $this->pageContent);
    }

    /** Recoit les données des thèmes médicaux d'un user puis lance la construction des blocs HTML
     * @param array $medicThemeList     Données des thèmes médicaux
    */
    public function buildOrder(array $medicThemeList)
    {
        $this->medicThemeList = $medicThemeList;

        $this->medicThemeCardListHTML = $this->medicThemeCardsBuilder();

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Création de l'ensemble des cards de thémes médicaux
     * @return string                   HTML des cards
     */
    private function medicThemeCardsBuilder(): string
    {
        $cardTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/medicTheme/medicThemeList/medicThemeCard.html');
        $cardsHTML = '';

        foreach ($this->medicThemeList as $themeData) {
            $cardTempHTML = $cardTemplate;
            $cardTempHTML = str_replace('{medicThemeID}', $themeData['medicThemeID'], $cardTempHTML);
            $cardTempHTML = str_replace('{medicThemeName}', $themeData['name'], $cardTempHTML);
            $cardsHTML .= $cardTempHTML;
        }

        return $cardsHTML;
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/medicTheme/medicThemeList/medicThemeList.html'),
            'medicThemeQty' => sizeof($this->medicThemeList),
            'medicThemeCards' => $this->medicThemeCardListHTML,
            'speMedicModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{medicThemeQty}', $this->contentSettingsList['medicThemeQty'], $this->pageContent);
        $this->pageContent = str_replace('{medicThemeCards}', $this->contentSettingsList['medicThemeCards'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
    }
}
