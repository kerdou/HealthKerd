<?php

namespace HealthKerd\View\medic\speMedic\speMedicList;

/** Construction puis affichage de la page listant les spécialités médicales utilisées par le user
 */
class SpeMedicListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $speMedicList = array();
    private string $speMedicCardListHTML = '';

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
            "pageTitle" => 'Spécialités médicales consultées',
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

    /** Recoit les données des thèmes médicaux d'un user puis lance la construction des blocs HTML
     * @param array $speMedicList     Données des spécialités médicales
    */
    public function buildOrder(array $speMedicList): void
    {
        $this->speMedicList = $speMedicList;

        $this->speMedicCardListHTML = $this->speMedicCardsBuilder();

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Création de l'ensemble des cards de spécialité médicale
     * @return string                   HTML des cards
     */
    private function speMedicCardsBuilder(): string
    {
        $cardTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/speMedic/speMedicList/speMedicCard.html');
        $cardsHTML = '';

        foreach ($this->speMedicList as $speData) {
            $cardTempHTML = $cardTemplate;
            $cardTempHTML = str_replace('{speMedicID}', $speData['speMedicID'], $cardTempHTML);
            $cardTempHTML = str_replace('{speMedicName}', $speData['name'], $cardTempHTML);
            $cardsHTML .= $cardTempHTML;
        }

        return $cardsHTML;
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/speMedic/speMedicList/speMedicList.html'),
            'speMedicQty' => sizeof($this->speMedicList),
            'speMedicCards' => $this->speMedicCardListHTML,
            'speMedicModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicQty}', $this->contentSettingsList['speMedicQty'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicCards}', $this->contentSettingsList['speMedicCards'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
    }
}
