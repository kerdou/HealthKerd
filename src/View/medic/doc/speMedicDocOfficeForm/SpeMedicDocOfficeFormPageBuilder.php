<?php

namespace HealthKerd\View\medic\doc\speMedicDocOfficeForm;

/** Construction puis affichage du formulaire de sélection de spécialités médicales et de cabinets médicaux
 */
class SpeMedicDocOfficeFormPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    protected string $pageContent = '';
    private string $formTemplate = '';
    private array $contentSettingsList = array();


    public function __construct()
    {
        parent::__construct();
        $this->pageElementsSettingsList();
        $this->pageElementsStringReplace(); // configuration de la page
    }

    public function __destruct()
    {
    }

    /** Liste des paramétres de la page, sans le contenu
     */
    private function pageElementsSettingsList(): void
    {
        $this->pageSettingsList = array(
            'headContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/globalLayout/head.html'),
            'pageTitle' => 'Spécialités médicales et cabinet d\'un professionnel de santé',
            'headerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/globalLayout/header.html'),
            'mainContainer' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/mainContainer.html'),
            'sidebarMenuUlContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/sidebarMenuUlContent.html'),
            'userFullName' => $_SESSION['firstName'] . ' ' . $_SESSION['lastName'],
            'scrollUpButton' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/scrollUpArrow.html'),
            'footerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/globalLayout/footer.html'),
            'BodyBottomDeclarations' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/globalLayout/BodyBottomDeclarations.html')
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

    /** Configuration de tous les élèments du formulaire
     */
    public function buildOrder(): void
    {
        $this->formTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/speMedicDocOfficeForm/speMedicDocOfficeForm.html');

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => $this->formTemplate,
            'speMedicModal' => '',
            'docModifModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
        $this->pageContent = str_replace('{docModifModal}', $this->contentSettingsList['docModifModal'], $this->pageContent);
    }
}
