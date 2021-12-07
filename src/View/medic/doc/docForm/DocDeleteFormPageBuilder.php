<?php

namespace HealthKerd\View\medic\doc\docForm;

class DocDeleteFormPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $docData = array();
    private array $checkStatusArray = array();

    public function __construct()
    {
        parent::__construct();
        /*
        $this->pageSettingsList = array(
            "pageTitle" => "Page de connexion"
        );
        */
    }


    public function __destruct()
    {
    }


    /** */
    public function dataReceiver(array $docData)
    {
        $this->docData = $docData;
        $this->buildOrder();
    }


    /** */
    private function buildOrder()
    {
        $this->builtContentHTML .= file_get_contents(__DIR__ . '../../../../../../templates/medic/doc/docFormTop.html');
        $this->builtContentHTML .= '<button type="submit" id="formValButton" name="formValButton" class="btn btn-secondary me-2" value="{formValButtonValue}">{formValButtonText}</button>';
        $this->builtContentHTML .= file_get_contents(__DIR__ . '../../../../../../templates/medic/doc/docFormBot.html');

        $this->checkStatusArray['dr'] = ($this->docData['title'] == 'dr') ? 'checked' : '';
        $this->checkStatusArray['mr'] = ($this->docData['title'] == 'mr') ? 'checked' : '';
        $this->checkStatusArray['mrs'] = ($this->docData['title'] == 'mrs') ? 'checked' : '';
        $this->checkStatusArray['ms'] = ($this->docData['title'] == 'ms') ? 'checked' : '';
        $this->checkStatusArray['none'] = ($this->docData['title'] == 'none') ? 'checked' : '';

        $this->stringReplacer();


        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }


    private function stringReplacer()
    {
        $this->builtContentHTML = str_replace('{formAction}', "index.php?controller=medic&subCtrlr=docPost&action=removeDoc&docID=" . $this->docData['docID'] . "", $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{formTitle}', 'Suppression d\'un professionnel de santé', $this->builtContentHTML);

        //Civilité
        $this->builtContentHTML = str_replace('{drChecked}', $this->checkStatusArray['dr'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{drDisabled}', 'disabled', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{mrChecked}', $this->checkStatusArray['mr'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mrDisabled}', 'disabled', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{mrsChecked}', $this->checkStatusArray['mrs'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mrsDisabled}', 'disabled', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{msChecked}', $this->checkStatusArray['ms'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{msDisabled}', 'disabled', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{noneChecked}', $this->checkStatusArray['none'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{noneDisabled}', 'disabled', $this->builtContentHTML);


        // Nom de famille
        $this->builtContentHTML = str_replace('{lastnameValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{lastnameValue}', $this->docData['lastName'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{lastNameReadOnly}', 'readonly', $this->builtContentHTML);

        // Prénom
        $this->builtContentHTML = str_replace('{firstnameValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{firstnameValue}', $this->docData['firstName'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{fistNameReadOnly}', 'readonly', $this->builtContentHTML);





        // Tel
        $this->builtContentHTML = str_replace('{telValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{telValue}', $this->docData['tel'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{telReadOnly}', 'readonly', $this->builtContentHTML);


        // Mail
        $this->builtContentHTML = str_replace('{mailValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mailValue}', $this->docData['mail'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mailReadOnly}', 'readonly', $this->builtContentHTML);





        // Site web perso
        $this->builtContentHTML = str_replace('{webpageValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{webpageValue}', $this->docData['webPage'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{webpageReadOnly}', 'readonly', $this->builtContentHTML);


        // Page Docotlib
        $this->builtContentHTML = str_replace('{doctolibpageValidity}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{doctolibpageValue}', $this->docData['doctolibPage'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{doctolibpageReadOnly}', 'readonly', $this->builtContentHTML);




        // Commentaires
        $this->builtContentHTML = str_replace('{commentContent}', $this->docData['comment'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{commentReadOnly}', 'readonly', $this->builtContentHTML);




        // Bouton de suppression
        $this->builtContentHTML = str_replace('{formValButtonValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{formValButtonText}', 'Supprimer', $this->builtContentHTML);

        // Bouton d'annulation
        $this->builtContentHTML = str_replace('{cancelHref}', "index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $this->docData['docID'] . "", $this->builtContentHTML);
    }
}
