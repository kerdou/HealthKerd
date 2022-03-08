<?php

namespace HealthKerd\Controller\login;

abstract class LoginCommonController
{
    public function __destruct()
    {
    }

    /** Récupération des données des 3 derniers clients et 3 derniers prospects puis affichage */
    public function displayLoginPage(): void
    {
        $loginView = new \HealthKerd\View\login\LoginPageBuilder();
        $loginView->buildOrder();
    }
}
