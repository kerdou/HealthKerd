<?php

namespace HealthKerd\View\medic\docOffice\docOfficeList;

/** Construction et affichage de la page listant les cabinets médicaux
 */
class DocOfficeListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $docOfficeList = array();
    private string $docOfficeCardsListHTML = '';

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
            "pageTitle" => 'Liste des cabinets médicaux consultés',
            'headerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/header.html'),
            'mainContainer' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/mainContainer.html'),
            'sidebarMenuUlContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/sidebarMenuUlContent.html'),
            'userFullName' => $_SESSION['firstName'] . ' ' . $_SESSION['lastName'],
            'scrollUpButton' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/scrollUpArrow.html'),
            'footerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/footer.html'),
            'BodyBottomDeclarations' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/BodyBottomDeclarations.html')
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
        $this->pageContent = str_replace('{BodyBottomDeclarations}', $this->pageSettingsList['BodyBottomDeclarations'], $this->pageContent);
    }

    /** Assemblage des blocs HTML
     * Puis configuration de la page et affichage du contenu
     */
    public function buildOrder(array $docOfficeList)
    {
        $this->docOfficeList = $docOfficeList;

        $this->docOfficeCardsListHTML = $this->docOfficeCardsBuilder(); // cards des docteurs

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Création de la liste des cards des cabinets médicaux
     * @return string      HTML de la liste des cabinets médicaux
    */
    private function docOfficeCardsBuilder(): string
    {
        $cardTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/docOffice/docOfficeList/docOfficeCard.html');
        $cardsHTML = '';

        foreach ($this->docOfficeList as $officeData) {
            $cardTempHTML = $cardTemplate;
            $cardTempHTML = str_replace('{docOfficeID}', $officeData['docOfficeID'], $cardTempHTML);
            $cardTempHTML = str_replace('{docOfficeName}', $officeData['name'], $cardTempHTML);
            $cardTempHTML = str_replace('{cityName}', $officeData['cityName'], $cardTempHTML);
            $cardsHTML .= $cardTempHTML;
        }

        return $cardsHTML;
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/docOffice/docOfficeList/docOfficeList.html'),
            'docOfficeQty' => sizeof($this->docOfficeList),
            'docOfficeCards' => $this->docOfficeCardsListHTML,
            'speMedicModal' => '',
            'docModifModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{docOfficeQty}', $this->contentSettingsList['docOfficeQty'], $this->pageContent);
        $this->pageContent = str_replace('{docOfficeCards}', $this->contentSettingsList['docOfficeCards'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
        $this->pageContent = str_replace('{docModifModal}', $this->contentSettingsList['docModifModal'], $this->pageContent);
    }
}
