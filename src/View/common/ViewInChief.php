<?php

namespace HealthKerd\View\common;

/** Classe mère des view */
abstract class ViewInChief
{
    protected string $pageContent; // Cumule le contenu qui sera affiché


    /** Configure le <title> de la page et passe l'icone du <nav> de la page en cours en active (classe Boostrap)
     * @param array $pageSettings Contientt le paramêtrage de la page.
     */
    protected function pageSetup(array $pageSettings)
    {
        $this->pageContent = str_replace("{pageTitle}", $pageSettings["pageTitle"], $this->pageContent);
        $this->pageContent = str_replace("{activeHome}", $pageSettings["activeHome"], $this->pageContent);
        $this->pageContent = str_replace("{activeCat}", $pageSettings["activeCat"], $this->pageContent);
        $this->pageContent = str_replace("{activeProsp}", $pageSettings["activeProsp"], $this->pageContent);
        $this->pageContent = str_replace("{activeClient}", $pageSettings["activeClient"], $this->pageContent);
    }


    /** Configure le titre du tableau, met la bonne adresse au <form action=""> et met le bon texte sur le bouton d'ajout
     * en haut et en bas du tableau quand la page est affichée sur mobile.
     * @param array $tableSettings array contenant le paramêtrage du tableau.
     */
    protected function tableSetup(array $tableSettings)
    {
        $this->pageContent = str_replace("{tableTitle}", $tableSettings["tableTitle"], $this->pageContent);
        $this->pageContent = str_replace("{addLink}", $tableSettings["addLink"], $this->pageContent);
        $this->pageContent = str_replace("{addLinkText}", $tableSettings["addLinkText"], $this->pageContent);
    }


    /** Affichage le contenu du pageContent */
    protected function pageDisplay()
    {
        echo $this->pageContent;
    }
}
