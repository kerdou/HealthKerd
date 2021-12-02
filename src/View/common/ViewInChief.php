<?php

namespace HealthKerd\View\common;

/** Classe mère des view */
abstract class ViewInChief
{
    protected string $topMainLayoutHTML = '';
    protected string $bottomMainLayoutHTML = '';
    protected string $pageContent = ''; // Cumule le contenu de tout ce qui sera affiché sur la page


    public function __construct()
    {
        $this->topMainLayoutAssembly();
        $this->bottomMainLayoutAssembly();
    }


    /** */
    protected function topMainLayoutAssembly()
    {
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/head.html');
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/header.html');
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/myContentOpener.html');
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/sidebar.html');
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/offCanvas-sidebar.html');
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/mainOpener.html');
    }


    /** */
    protected function bottomMainLayoutAssembly()
    {
        $this->bottomMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/myContentRowAndMainFinisher.html');
        $this->bottomMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/modalStoreOpener.html');
        $this->bottomMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../templates/modals/speMedicModals.html');
        $this->bottomMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/pageBottom.html');
    }


    /** Configure le <title> de la page et passe l'icone du <nav> de la page en cours en active (classe Boostrap)
     * @param array $pageSettings Contientt le paramêtrage de la page.
     */
    protected function pageSetup(array $pageSettings)
    {
        $this->pageContent = str_replace("{pageTitle}", $pageSettings["pageTitle"], $this->pageContent);
    }


    /** Affichage le contenu du pageContent */
    protected function pageDisplay()
    {
        echo $this->pageContent;
    }
}
