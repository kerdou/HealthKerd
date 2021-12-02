<?php

namespace HealthKerd\Controller\login;

abstract class LoginCommonController
{
    private object $loginView; // Affichage des tableaux de la section accueil

    public function __construct()
    {
    }


    public function __destruct()
    {
    }

    /** Récupération des données des 3 derniers clients et 3 derniers prospects puis affichage */
    public function displayLoginPage()
    {
        $this->loginView = new \HealthKerd\View\login\LoginPageBuilder();
        $this->loginView->buildOrder();
    }
}
