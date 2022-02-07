<?php

namespace HealthKerd\View\common;

/** Classe mère des view */
abstract class ViewInChief
{
    protected string $topMainLayoutHTML = '';
    protected string $bottomMainLayoutHTML = '';
    protected string $pageContent = ''; // Cumule le contenu de tout ce qui sera affiché sur la page


    /** Ajoute tout ce qui se trouve au dessus et en dessous du content
     */
    public function __construct()
    {
        $this->topMainLayoutAssembly();
        $this->bottomMainLayoutAssembly();
    }

    public function __destruct()
    {
    }

    /** HTML précédent le Main-content
     */
    protected function topMainLayoutAssembly()
    {
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/head.html'); // Head du HTML
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/header.html'); // En-tête de page
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/myContentOpener.html'); // DIV necessaires au positionnement du Content. C'est à dire Sidebar + Main-content
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/sidebar.html'); // Menu latéral en mode desktop
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/offCanvas-sidebar.html'); //Menu latéral en mode mobile, reçoit une copie du sidebar desktop
        $this->topMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/mainOpener.html'); // DIV necessaires pour le positionnement du Main-content
    }


    /** HTML placé à la suite du Main-content
     */
    protected function bottomMainLayoutAssembly()
    {
        $this->bottomMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/myContentRowAndMainFinisher.html'); // fermeture de Main-content et Content
        $this->bottomMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/modalStoreOpener.html'); // ouverture de la DIV de stockage des boites modales
        $this->bottomMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../templates/modals/speMedicModals.html'); // template des modales de spécialités médicales
        $this->bottomMainLayoutHTML .= file_get_contents(__DIR__ . '../../../../public/html/globalLayout/pageBottom.html'); // fermeture de la DIV de boites modales et de tout le HTML
    }


    /** Configure le <title> de la page et passe l'icone du <nav> de la page en cours en active (classe Boostrap)
     * @param array $pageSettings   Contient le paramêtrage de la page.
     */
    protected function pageSetup(array $pageSettings)
    {
        $this->pageContent = str_replace("{pageTitle}", $pageSettings["pageTitle"], $this->pageContent);
    }


    /** Affichage le contenu de la page
    */
    protected function pageDisplay()
    {
        echo $this->pageContent;
    }
}
