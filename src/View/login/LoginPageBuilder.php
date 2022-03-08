<?php

namespace HealthKerd\View\login;

/** Assemblage de la page de login avant affichage
 */
class LoginPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();

    public function __construct()
    {
        parent::__construct();
        $this->pageSettingsList = array(
            'headContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/head.html'),
            'pageTitle' => 'Page de connexion',
            'headerContent' => '',
            'mainContainer' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/unlogged/login/main.html'),
            'footerContent' => '',
            'BodyBottomDeclarations' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/BodyBottomDeclarations.html')
        );
    }

    public function __destruct()
    {
    }

    /** Configure le <title> de la page et passe l'icone du <nav> de la page en cours en active (classe Boostrap)
     * @param array $pageSettings   Contient le paramêtrage de la page.
     */
    protected function pageSetup(array $pageSettings): void
    {
        $this->pageContent = str_replace("{headContent}", $pageSettings["headContent"], $this->pageContent);
        $this->pageContent = str_replace("{pageTitle}", $pageSettings["pageTitle"], $this->pageContent);
        $this->pageContent = str_replace("{headerContent}", $pageSettings["headerContent"], $this->pageContent);
        $this->pageContent = str_replace("{mainContainer}", $pageSettings["mainContainer"], $this->pageContent);
        $this->pageContent = str_replace("{footerContent}", $pageSettings["footerContent"], $this->pageContent);
        $this->pageContent = str_replace("{BodyBottomDeclarations}", $pageSettings["BodyBottomDeclarations"], $this->pageContent);
    }

    public function buildOrder()
    {
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }
}
