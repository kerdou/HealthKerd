<?php

namespace HealthKerd\View\medic\doc\docForm;

class DocAddFormPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';


    public function __construct()
    {
        parent::__construct();

        $this->pageSettingsList = array(
            "pageTitle" => "Création d'un professionnel de santé"
        );
    }


    public function __destruct()
    {
    }


    /** */
    public function dataReceiver()
    {
        $this->buildOrder();
    }


    /** */
    private function buildOrder()
    {
        $this->builtContentHTML .= file_get_contents(__DIR__ . '../../../../../../templates/medic/doc/docFormTop.html');
        $this->builtContentHTML .= '<button type="button" id="formSubmitButton" name="formSubmitButton" class="btn btn-secondary me-2" value="{formSubmitButtonValue}">{formSubmitButtonText}</button>';
        $this->builtContentHTML .= '<button type="button" id="formResetButton" name="formResetButton" class="btn btn-secondary me-2">Réinitialiser</button>';
        $this->builtContentHTML .= file_get_contents(__DIR__ . '../../../../../../templates/medic/doc/docFormBot.html');
        $this->stringReplacer();

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }


    private function stringReplacer()
    {

        $this->builtContentHTML = str_replace('{formAction}', 'index.php?controller=medic&subCtrlr=docPost&action=addDoc', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{formTitle}', 'Création d\'un professionnel de santé', $this->builtContentHTML);

        //Civilité
        $this->builtContentHTML = str_replace('{drChecked}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{drDisabled}', '', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{mrChecked}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mrDisabled}', '', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{mrsChecked}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mrsDisabled}', '', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{msChecked}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{msDisabled}', '', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{noneChecked}', 'checked', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{noneDisabled}', '', $this->builtContentHTML);


        // Nom de famille
        $this->builtContentHTML = str_replace('{lastnameValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{lastnameValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{lastNameReadOnly}', '', $this->builtContentHTML);

        // Prénom
        $this->builtContentHTML = str_replace('{firstnameValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{firstnameValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{fistNameReadOnly}', '', $this->builtContentHTML);





        // Tel
        $this->builtContentHTML = str_replace('{telValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{telValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{telReadOnly}', '', $this->builtContentHTML);

        // Mail
        $this->builtContentHTML = str_replace('{mailValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mailValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mailReadOnly}', '', $this->builtContentHTML);





        // Site web perso
        $this->builtContentHTML = str_replace('{webpageValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{webpageValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{webpageReadOnly}', '', $this->builtContentHTML);

        // Page Docotlib
        $this->builtContentHTML = str_replace('{doctolibpageValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{doctolibpageValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{doctolibpageValue}', '', $this->builtContentHTML);




        // Commentaires
        $this->builtContentHTML = str_replace('{commentContent}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{commentReadOnly}', '', $this->builtContentHTML);




        // Bouton de création
        $this->builtContentHTML = str_replace('{formSubmitButtonValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{formSubmitButtonText}', 'Créer', $this->builtContentHTML);

        // Bouton d'annulation
        $this->builtContentHTML = str_replace('{cancelHref}', 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp', $this->builtContentHTML);
    }
}
