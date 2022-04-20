<?php

namespace HealthKerd\View\medic\eventCats\allEventsRegardingOneCat;

/** Construction et affichage de tous les events liés à une catégorie d'événements
*/
class AllEventsRegardingOneCatPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $eventsContent = '';

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
            "pageTitle" => '&Eacute;vénements médicaux liés à une catégorie',
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

    /** Recoit les données de tous les rendez-vous puis lance la construction du HTML de ce contenu
     * * On construit d'abord les events futurs
     * * On construit ensuite les events passés
     * @param array $eventsData     Donnée réassamblée et réagencée dans le Processor pour faciliter la création du HTML
     */
    public function buildOrder(array $eventsData): void
    {
        $this->allEventsBuilder($eventsData);

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Construction des blocs HTML passés et futurs, s'il y en a
     * @param array $eventsData     Données des events
     */
    private function allEventsBuilder(array $eventsData): void
    {
        if ((sizeof($eventsData['futureEvents']) == 0) && (sizeof($eventsData['pastEvents']) == 0)) {
            $this->eventsContent = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/eventCats/allEventsRegardingOneCat/noEventsContent.html');
        } else {
            if (sizeof($eventsData['futureEvents']) > 0) {
                $futureEventsContentTemplate = $this->futureEventsBuilder($eventsData['futureEvents']);
            } else {
                $futureEventsContentTemplate = '';
            }

            if (sizeof($eventsData['pastEvents']) > 0) {
                $pastEventsContentTemplate = $this->pastEventsBuilder($eventsData['pastEvents']);
            } else {
                $pastEventsContentTemplate = '';
            }

            $this->eventsContent = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/eventCats/allEventsRegardingOneCat/eventsContent.html');
            $this->eventsContent = str_replace('{futureEventsAccordion}', $futureEventsContentTemplate, $this->eventsContent);
            $this->eventsContent = str_replace('{pastEventsAccordion}', $pastEventsContentTemplate, $this->eventsContent);
        }
    }

    /** Construction des éléments HTML des events à venir
     * @param array $futureEventsData       Données des events futurs
     * @return string                       Blocs HTML des events futurs
     */
    private function futureEventsBuilder(array $futureEventsData): string
    {
        $eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
        $futureEventsAccordionHTML = $eventsBuilder->eventBuildOrder($futureEventsData);

        $futureEventsContentTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/futureEvents/futureEvents.html');
        $futureEventsContentTemplate = str_replace('{futureEventsQty}', sizeof($futureEventsData), $futureEventsContentTemplate);
        $futureEventsContentTemplate = str_replace('{futureEventsAccordion}', $futureEventsAccordionHTML, $futureEventsContentTemplate);

        return $futureEventsContentTemplate;
    }

    /** Construction des éléments HTML des events passés
     * @param array $pastEventsData     Données des events passés
     * @return string                   Blocs HTML des events passés
     */
    private function pastEventsBuilder(array $pastEventsData): string
    {
        $eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
        $pastEventsAccordionHTML = $eventsBuilder->eventBuildOrder($pastEventsData);

        $pastEventsContentTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/pastEvents/pastEvents.html');
        $pastEventsContentTemplate = str_replace('{pastEventsQty}', sizeof($pastEventsData), $pastEventsContentTemplate);
        $pastEventsContentTemplate = str_replace('{pastEventsAccordion}', $pastEventsAccordionHTML, $pastEventsContentTemplate);

        return $pastEventsContentTemplate;
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/eventCats/allEventsRegardingOneCat/allEventsRegardingOneCat.html'),
            'eventsContent' => $this->eventsContent,
            'speMedicModal' => '',
            'docModifModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{eventsContent}', $this->contentSettingsList['eventsContent'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
        $this->pageContent = str_replace('{docModifModal}', $this->contentSettingsList['docModifModal'], $this->pageContent);
    }
}
