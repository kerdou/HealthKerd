<?php

namespace HealthKerd\View\home;

/** Assemblage de la homepage avant affichage
 * Elle n'affiche que les futurs rendez-vous
 */
class HomePageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $contentSettingsList = array();
    private string $futureEventsContentHTML = '';

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
            'headContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/head.html'),
            "pageTitle" => 'Accueil',
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

    /** Recoit les données des futurs rendez-vous puis lance la construction du HTML de ce contenu
     * @param array $eventsData     Donnée réassamblée et réagencée dans le Processor pour faciliter la création du HTML
     */
    public function buildOrder(array $eventsData): void
    {
        $this->futureEventsContentHTML  = $this->futureEventsBuilder($eventsData);

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Construction des éléments HTML des events à venir
     * @param array $futureEventsData       Données des events futurs
     * @return string                       Blocs HTML des events futurs
     */
    private function futureEventsBuilder(array $eventsData): string
    {
        if (sizeof($eventsData['futureEvents']) > 0) {
            $eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
            $futureEventsAccordionHTML = $eventsBuilder->eventBuildOrder($eventsData['futureEvents']);

            $futureEventsContentTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/futureEvents/futureEvents.html');
            $futureEventsContentTemplate = str_replace('{futureEventsQty}', sizeof($eventsData['futureEvents']), $futureEventsContentTemplate);
            $futureEventsContentTemplate = str_replace('{futureEventsAccordion}', $futureEventsAccordionHTML, $futureEventsContentTemplate);
        } else {
            $futureEventsContentTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/futureEvents/noFutureEvents.html');
        }

        return $futureEventsContentTemplate;
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/home/home.html'),
            'futureEventsContent' => $this->futureEventsContentHTML,
            'speMedicModal' => '',
            'docModifModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{futureEventsContent}', $this->contentSettingsList['futureEventsContent'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
        $this->pageContent = str_replace('{docModifModal}', $this->contentSettingsList['docModifModal'], $this->pageContent);
    }
}
