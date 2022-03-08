<?php

namespace HealthKerd\View\medic\eventCats\eventCatsList;

/** Construction et affichage de la page listant les catégories d'événements
 */
class EventCatsListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $eventCatCardsHTML = '';
    private array $eventCatsList = array();

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
            "pageTitle" => 'Catégories d\'événements médicaux',
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

    /**
     */
    public function buildOrder(array $eventCatsList)
    {
        $this->eventCatsList = $eventCatsList;
        $this->eventCatCardsHTML = $this->eventCatCardsBuilder($this->eventCatsList);

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Création de la liste des cards des catégories d'événements
     * @param array $eventCatsList      Données des catégories d'événements
     * @return string                   HTML de la liste des catégories d'événements
    */
    private function eventCatCardsBuilder(array $eventCatsList)
    {
        $catCardTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/eventCats/eventCatsList/eventCatCard.html');
        $catCardHTML = '';

        foreach ($eventCatsList as $speData) {
            $tempCard = $catCardTemplate;
            $tempCard = str_replace('{medicEventCatID}', $speData['medicEventCatID'], $tempCard);
            $tempCard = str_replace('{catName}', $speData['name'], $tempCard);
            $catCardHTML .= $tempCard;
        }

        return $catCardHTML;
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/eventCats/eventCatsList/eventCatsList.html'),
            'eventCatsQty' => sizeof($this->eventCatsList),
            'eventCatCards' => $this->eventCatCardsHTML,
            'speMedicModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{eventCatsQty}', $this->contentSettingsList['eventCatsQty'], $this->pageContent);
        $this->pageContent = str_replace('{eventCatCards}', $this->contentSettingsList['eventCatCards'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
    }
}
