<?php

namespace HealthKerd\View\home;

/** Assemblage de la homepage avant affichage
 * Elle n'affiche que les futurs rendez-vous
 */
class HomePageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $eventsData = array();
    private string $builtContentHTML = '';
    private string $futureEventsHTML = '';

    private object $eventsBuilder;

    public function __construct()
    {
        parent::__construct();

        $this->pageSettingsList = array(
            "pageTitle" => "Accueil"
        );
    }

    public function __destruct()
    {
    }

    /** Recoit les données des futurs rendez-vous puis lance la construction du HTML de ce contenu
     * @param array $medicEvtProcessedDataStore     Donnée réassamblée et réagencée dans le Processor pour faciliter la création du HTML
     */
    public function dataReceiver(array $medicEvtProcessedDataStore)
    {
        $this->eventsData = $medicEvtProcessedDataStore;
        $this->eventsBuilder = new \HealthKerd\View\medic\eventsBuilder\EventsBuilder();
        $this->futureEventsHTML = $this->eventsBuilder->eventBuildOrder($this->eventsData['futureEvents']);
        $this->buildOrder();
    }

    /** Dernières configurations puis assemblage final de la page avant affichage
     */
    private function buildOrder()
    {
        $this->builtContentHTML .= '<h3>&Eacute;vénements médicaux à venir: ' . sizeof($this->eventsData['futureEvents']) . '</h3>';
        $this->builtContentHTML .= $this->futureEventsHTML;

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }
}
