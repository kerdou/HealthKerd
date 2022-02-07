<?php

namespace HealthKerd\View\medic\doc\docForm;

/** Construction puis affichage du formulaire de modification de docteur après un echec
 */
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

        $this->pageSettingsList = array(
            "pageTitle" => "Modification d'un professionnel de santé"
        );
    }

    public function __destruct()
    {
    }

    /** Lance la construction du HTML
     * @param array $docData        Donnée du docteur à afficher dans le formulaire
     * @param array $checksArray    Liste des classes à ajouter sur les élèments de message d'erreur à faire apparaitre
     * @param string $docID         ID du docteur à modifier
    */
    public function dataReceiver(array $docData, array $checksArray, string $docID)
    {
        $this->docData = $docData;
        $this->checksArray = $checksArray;
        $this->docID = $docID;

        $this->buildOrder();
    }

    /** Configuration de tous les élèments du formulaire
     */
    private function buildOrder()
    {
        $this->builtContentHTML .= file_get_contents(__DIR__ . '../../../../../../templates/medic/doc/docFormTop.html'); // partie haute du template de formulaire de docteur
        $this->builtContentHTML .= '<button type="button" id="formSubmitButton" name="formSubmitButton" class="btn btn-secondary me-2" value="{formSubmitButtonValue}">{formSubmitButtonText}</button>';
        $this->builtContentHTML .= '<button type="button" id="formResetButton" name="formResetButton" class="btn btn-secondary me-2">Réinitialiser</button>';
        $this->builtContentHTML .= '<a href="{formSupprButtonValue}" id="formSupprButton" name="formSupprButton" class="btn btn-secondary me-2" >Supprimer</a>';
        $this->builtContentHTML .= file_get_contents(__DIR__ . '../../../../../../templates/medic/doc/docFormBot.html'); // partie basse du template de formulaire de docteur

        // remplacement du contenu de civilité pour appliquer la classe "checked" au Button Group de civilité
        $this->checkStatusArray['dr'] = ($this->docData['title'] == 'dr') ? 'checked' : '';
        $this->checkStatusArray['mr'] = ($this->docData['title'] == 'mr') ? 'checked' : '';
        $this->checkStatusArray['mrs'] = ($this->docData['title'] == 'mrs') ? 'checked' : '';
        $this->checkStatusArray['ms'] = ($this->docData['title'] == 'ms') ? 'checked' : '';
        $this->checkStatusArray['none'] = ($this->docData['title'] == 'none') ? 'checked' : '';

        // ajout des classes sur les élèments servant à l'affichage des messages d'erreur
        $this->validityArray['lastname'] = ($this->checksArray['lastname'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['firstname'] = ($this->checksArray['firstname'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['tel'] = ($this->checksArray['tel'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['mail'] = ($this->checksArray['mail'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['webpage'] = ($this->checksArray['webpage'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['doctolibpage'] = ($this->checksArray['doctolibpage'] == true) ? 'is-valid' : 'is-invalid';

        $this->stringReplacer();

        $this->pageContent = $this->topMainLayoutHTML . $this->builtContentHTML . $this->bottomMainLayoutHTML;
        $this->pageSetup($this->pageSettingsList); // configuration de la page
        $this->pageDisplay();
    }

    /** Configuration de tous les élèments du formulaire
     */
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
        $this->builtContentHTML = str_replace('{formSubmitButtonValue}', '', $this->builtContentHTML);
        $this->builtContentHTML = str_replace('{formSubmitButtonText}', 'Modifier', $this->builtContentHTML);

        // Bouton de suppression de compte
        $this->builtContentHTML = str_replace('{formSupprButtonValue}', "index.php?controller=medic&subCtrlr=doc&action=showDocDeleteForm&docID=" . $this->docID . "", $this->builtContentHTML);

        // Bouton d'annulation
        $this->builtContentHTML = str_replace('{cancelHref}', "index.php?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=" . $this->docID . "", $this->builtContentHTML);
    }
}
