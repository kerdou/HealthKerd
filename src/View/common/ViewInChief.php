<?php

namespace HealthKerd\View\common;

/** Classe mère des view */
abstract class ViewInChief
{
    protected string $pageContent = ''; // Cumule le contenu de tout ce qui sera affiché sur la page

    /** Ajoute tout ce qui se trouve au dessus et en dessous du content
     */
    public function __construct()
    {
        $this->globalLayoutAssembly();
    }

    public function __destruct()
    {
    }

    /** Construction de la structure de base de la page
     */
    protected function globalLayoutAssembly()
    {
        $this->pageContent = file_get_contents($_ENV['APPROOTPATH'] . 'public/HTML/globalLayout.html');
    }

    /** Affichage le contenu de la page
    */
    protected function pageDisplay()
    {
        echo $this->pageContent;
    }
}
