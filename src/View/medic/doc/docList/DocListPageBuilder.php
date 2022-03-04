<?php

namespace HealthKerd\View\medic\doc\docList;

/** Assemblage de la liste des docteurs avant affichage
 */
class DocListPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    protected string $pageContent = '';
    private array $docList = array();
    private array $speMedicBadgeList = array();

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
            "pageTitle" => 'Professionnels de santé consultés',
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

    /** Assemblage des blocs HTML
     * Puis configuration de la page et affichage du contenu
     */
    public function buildOrder(array $docList, array $speMedicBadgeList)
    {
        $this->docList = $docList;
        $this->speMedicBadgeList = $speMedicBadgeList;

        $this->docCardsListHTML = $this->docCardsListBuilder($this->docList); // cards des docteurs
        $this->speMedicBadgeListHTML = $this->speMedicBadgesListBuilder($this->speMedicBadgeList); // badges de spécialités médicales

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Construction des cards de chaque docteur
     * @param array $docListData    Liste des données d'un docteur
     * @return string               HTML des cards de tous les docteurs
     */
    private function docCardsListBuilder(array $docListData): string
    {
        $docCompletedCardsHTML = '';

        foreach ($docListData as $value) {
            $docCardTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/docList/docCard.html');
            $speMedicBadgesHTML = $this->docCardSpeMedicBadgeBuilder($value['speMedicList']);

            $docCardTemplate = str_replace('{docID}', $value['docID'], $docCardTemplate);
            $docCardTemplate = str_replace('{docFullNameSentence}', $value['fullNameSentence'], $docCardTemplate);
            $docCardTemplate = str_replace('{speMedicBadges}', $speMedicBadgesHTML, $docCardTemplate);

            $docCompletedCardsHTML .= $docCardTemplate;
        }

        return $docCompletedCardsHTML;
    }

    /** Badges non-cliquables de spécialités médicales rattachées à un doc
     * @param array $speMedicList       Liste des spécialités médicales rattachées à un doc
     * @return string                   HTML des badges non-cliquables de spécialités médicales
     */
    private function docCardSpeMedicBadgeBuilder(array $speMedicList): string
    {
        $unclickableSpeMedicBadgeTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/docSpeMedic/unclickableDocSpeMedic.html');
        $speMedicBadgesHTML = '';

        foreach ($speMedicList as $value) {
            $speMedicBadgesTempHTML = str_replace('{speMedicName}', $value['name'], $unclickableSpeMedicBadgeTemplate);
            $speMedicBadgesHTML .= $speMedicBadgesTempHTML;
        }

        return $speMedicBadgesHTML;
    }

    /** Badges cliquables des spé medicales utilisées par le user
     * @param array $speMedicBadgeListData      Liste des spécialités médicales utilisées par le user
     * @return string                           HTML des badges cliquables de spécialités médicales
     */
    private function speMedicBadgesListBuilder(array $speMedicBadgeListData): string
    {
        $speMedicBadgeTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/docSpeMedic/unclickableDocSpeMedic.html');
        $speMedicBadgesListHTML = '';

        foreach ($speMedicBadgeListData as $value) {
            $speMedicBadgesTemp = str_replace('{speMedicName}', $value['speMedicName'], $speMedicBadgeTemplate);
            $speMedicBadgesListHTML .= $speMedicBadgesTemp;
        }

        return $speMedicBadgesListHTML;
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/docList/docList.html'),
            'docQty' => sizeof($this->docList),
            'docCardsList' => $this->docCardsListHTML,
            'addDocButton' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/docList/addDocButton.html'),
            'speMedicBadgeListQty' => sizeof($this->speMedicBadgeList),
            'speMedicBadgeList' => $this->speMedicBadgeListHTML,
            'speMedicModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{docQty}', $this->contentSettingsList['docQty'], $this->pageContent);
        $this->pageContent = str_replace('{docCardsList}', $this->contentSettingsList['docCardsList'], $this->pageContent);
        $this->pageContent = str_replace('{addDocButton}', $this->contentSettingsList['addDocButton'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicBadgeListQty}', $this->contentSettingsList['speMedicBadgeListQty'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicBadgeList}', $this->contentSettingsList['speMedicBadgeList'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
    }
}
