<?php

namespace HealthKerd\View\login;

/** Assemblage de la page de login avant affichage
 */
class LoginPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();

    public function __construct()
    {
        $this->pageSettingsList = array(
            "pageTitle" => "Page de connexion"
        );
    }

    public function __destruct()
    {
    }

    public function buildOrder()
    {
        $this->pageContent = file_get_contents(__DIR__ . '../../../../public/html/login/login.html'); // HTML de la page de login
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }
}
