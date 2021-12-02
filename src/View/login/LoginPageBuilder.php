<?php

namespace HealthKerd\View\login;

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
        //$this->pageContent = '<h1>WELCOME TO LOGIN PAGE!!!!!</h1>';
        $this->pageContent = file_get_contents(__DIR__ . '../../../../public/html/login/login.html');

        $this->pageDisplay();
    }
}
