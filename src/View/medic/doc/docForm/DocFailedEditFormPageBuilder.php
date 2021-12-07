<?php

namespace HealthKerd\View\medic\doc\docForm;

class DocFailedEditFormPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private string $builtContentHTML = '';
    private array $docData = array();
    private array $checkStatusArray = array();
    private array $checksArray = array();
    private array $validityArray = array();
    private string $docID;

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
    public function dataReceiver(array $docData, array $checksArray, string $docID)
    {
        $this->docData = $docData;
        $this->checksArray = $checksArray;
        $this->docID = $docID;

        $this->buildOrder();
    }


    /** */
    private function buildOrder()
    {
        $this->builtContentHTML .= file_get_contents(__DIR__ . '../../../../../../templates/medic/doc/docFormTop.html');
        $this->builtContentHTML .= '<button type="submit" id="formValButton" name="formValButton" class="btn btn-secondary me-2" value="{formValButtonValue}">{formValButtonText}</button>';
        $this->builtContentHTML .= '<button type="reset" id="formResetButton" name="formResetButton" class="btn btn-secondary me-2">Réinitialiser</button>';
        $this->builtContentHTML .= '<a href="{formSupprButtonValue}" id="formSupprButton" name="formSupprButton" class="btn btn-secondary me-2" >Supprimer</a>';
        $this->builtContentHTML .= file_get_contents(__DIR__ . '../../../../../../templates/medic/doc/docFormBot.html');

        $this->checkStatusArray['dr'] = ($this->docData['title'] == 'dr') ? 'checked' : '';
        $this->checkStatusArray['mr'] = ($this->docData['title'] == 'mr') ? 'checked' : '';
        $this->checkStatusArray['mrs'] = ($this->docData['title'] == 'mrs') ? 'checked' : '';
        $this->checkStatusArray['ms'] = ($this->docData['title'] == 'ms') ? 'checked' : '';
        $this->checkStatusArray['none'] = ($this->docData['title'] == 'none') ? 'checked' : '';

        $this->validityArray['lastname'] = ($this->checksArray['lastname'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['firstname'] = ($this->checksArray['firstname'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['tel'] = ($this->checksArray['tel'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['mail'] = ($this->checksArray['mail'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['webpage'] = ($this->checksArray['webpage'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['doctolibpage'] = ($this->checksArray['doctolibpage'] == true) ? 'is-valid' : 'is-invalid';


        $this->stringReplacer();
        //var_dump($this->builtContentHTML);


        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        //$this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }


    private function stringReplacer()
    {
        $this->builtContentHTML = str_replace('{formAction}', "index.php?controller=medic&subCtrlr=docPost&action=editDoc&docID=" . $this->docID . "", $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{formTitle}', 'Modification d\'un professionnel de santé', $this->builtContentHTML);


        //Civilité
        $this->builtContentHTML = str_replace('{drChecked}', $this->checkStatusArray['dr'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{drDisabled}', '', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{mrChecked}', $this->checkStatusArray['mr'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mrDisabled}', '', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{mrsChecked}', $this->checkStatusArray['mrs'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mrsDisabled}', '', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{msChecked}', $this->checkStatusArray['ms'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{msDisabled}', '', $this->builtContentHTML);

        $this->builtContentHTML = str_replace('{noneChecked}', $this->checkStatusArray['none'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{noneDisabled}', '', $this->builtContentHTML);


        // Nom de famille
        $this->builtContentHTML = str_replace('{lastnameValidity}', $this->validityArray['lastname'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{lastnameValue}', $this->docData['lastname'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{lastNameReadOnly}', '', $this->builtContentHTML);

        // Prénom
        $this->builtContentHTML = str_replace('{firstnameValidity}', $this->validityArray['firstname'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{firstnameValue}', $this->docData['firstname'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{fistNameReadOnly}', '', $this->builtContentHTML);


        // Tel
        $this->builtContentHTML = str_replace('{telValidity}', $this->validityArray['tel'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{telValue}', $this->docData['tel'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{telReadOnly}', '', $this->builtContentHTML);

        // Mail
        $this->builtContentHTML = str_replace('{mailValidity}', $this->validityArray['mail'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mailValue}', $this->docData['mail'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{mailReadOnly}', '', $this->builtContentHTML);




        // Site web perso
        $this->builtContentHTML = str_replace('{webpageValidity}', $this->validityArray['webpage'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{webpageValue}', $this->docData['webpage'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{webpageReadOnly}', '', $this->builtContentHTML);

        // Page Docotlib
        $this->builtContentHTML = str_replace('{doctolibpageValidity}', $this->validityArray['doctolibpage'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{doctolibpageValue}', $this->docData['doctolibpage'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{doctolibpageValue}', '', $this->builtContentHTML);




        // Commentaires
        $this->builtContentHTML = str_replace('{commentContent}', $this->docData['comment'], $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{commentReadOnly}', '', $this->builtContentHTML);




        // Bouton de Modification
        $this->builtContentHTML = str_replace('{formValButtonValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{formValButtonText}', 'Modifier', $this->builtContentHTML);

        // Bouton de suppression de compte {formSupprButtonValue}
        $this->builtContentHTML = str_replace('{formSupprButtonValue}', "index.php?controller=medic&subCtrlr=doc&action=showDocDeleteForm&docID=" . $this->docID . "", $this->builtContentHTML);

        // Bouton d'annulation
        $this->builtContentHTML = str_replace('{cancelHref}', "index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $this->docID . "", $this->builtContentHTML);
    }
}
