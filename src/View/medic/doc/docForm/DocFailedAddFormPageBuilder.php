<?php

namespace HealthKerd\View\medic\doc\docForm;

/** Construction puis affichage du formulaire d'ajout de docteur après un echec
 */
class DocFailedAddFormPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    protected string $pageContent = '';
    private array $docData = array();
    private array $checksArray = array();
    private string $formTemplate = '';
    private array $checkStatusArray = array();

    public function __construct()
    {
        parent::__construct();
        $this->pageElementsSettingsList();
        $this->pageElementsStringReplace(); // configuration de la page
    }

    public function __destruct()
    {
    }

    /** Liste des paramétres de la page, sans le contenu
     */
    private function pageElementsSettingsList(): void
    {
        $this->pageSettingsList = array(
            'headContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/head.html'),
            'pageTitle' => 'Création d\'un professionnel de santé',
            'headerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/header.html'),
            'mainContainer' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/mainContainer.html'),
            'sidebarMenuUlContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/sidebarMenuUlContent.html'),
            'userFullName' => $_SESSION['firstName'] . ' ' . $_SESSION['lastName'],
            'scrollUpButton' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/scrollUpArrow.html'),
            'footerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/footer.html'),
            'BodyBottomDeclarations' => file_get_contents($_ENV['APPROOTPATH'] . 'public/html/BodyBottomDeclarations.html')
        );
    }

    /** Application de tous les paramétres listés dans $pageSettingsList
     */
    private function pageElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{headContent}', $this->pageSettingsList['headContent'], $this->pageContent);
        $this->pageContent = str_replace('{pageTitle}', $this->pageSettingsList['pageTitle'], $this->pageContent);
        $this->pageContent = str_replace('{headerContent}', $this->pageSettingsList['headerContent'], $this->pageContent);
        $this->pageContent = str_replace('{mainContainer}', $this->pageSettingsList['mainContainer'], $this->pageContent);
        $this->pageContent = str_replace('{sidebarMenuUlContent}', $this->pageSettingsList['sidebarMenuUlContent'], $this->pageContent);
        $this->pageContent = str_replace('{userFullName}', $this->pageSettingsList['userFullName'], $this->pageContent);
        $this->pageContent = str_replace('{scrollUpButton}', $this->pageSettingsList['scrollUpButton'], $this->pageContent);
        $this->pageContent = str_replace('{footerContent}', $this->pageSettingsList['footerContent'], $this->pageContent);
        $this->pageContent = str_replace('{BodyBottomDeclarations}', $this->pageSettingsList['BodyBottomDeclarations'], $this->pageContent);
    }

    /** Configuration de tous les élèments du formulaire
     */
    public function buildOrder(array $docData, array $checksArray): void
    {
        $this->docData = $docData;
        $this->checksArray = $checksArray;

        $this->formTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/forms/docForm.html');
        $this->formConfLauncher();

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Ensemble des fonctions de configuration du forum
     */
    private function formConfLauncher(): void
    {
        $this->formActionAndTitleSetup();
        $this->checkStatusConverter();
        $this->checkStatusSetup();
        $this->validityStatusConverter();
        $this->namesSetup();
        $this->telAndMailSetup();
        $this->webLinksSetup();
        $this->commentsSetup();
        $this->formButtonsSetup();
    }

    /** Configuration du lien d'action et du titre de form
     */
    private function formActionAndTitleSetup(): void
    {
        $this->formTemplate = str_replace('{formAction}', 'index.php?controller=medic&subCtrlr=docPost&action=addDoc', $this->formTemplate);
        $this->formTemplate = str_replace('{formTitle}', 'Création d\'un professionnel de santé', $this->formTemplate);
    }

    /** Remplacement du contenu de civilité pour appliquer la classe "checked" au Button Group de civilité
     */
    private function checkStatusConverter(): void
    {
        $this->checkStatusArray['dr'] = ($this->docData['title'] == 'dr') ? 'checked' : '';
        $this->checkStatusArray['mr'] = ($this->docData['title'] == 'mr') ? 'checked' : '';
        $this->checkStatusArray['mrs'] = ($this->docData['title'] == 'mrs') ? 'checked' : '';
        $this->checkStatusArray['ms'] = ($this->docData['title'] == 'ms') ? 'checked' : '';
        $this->checkStatusArray['none'] = ($this->docData['title'] == 'none') ? 'checked' : '';
    }

    /** Configuration de la barre de civilité
     */
    private function checkStatusSetup(): void
    {
        $this->formTemplate = str_replace('{drChecked}', $this->checkStatusArray['dr'], $this->formTemplate);
        $this->formTemplate = str_replace('{drDisabled}', '', $this->formTemplate);

        $this->formTemplate = str_replace('{mrChecked}', $this->checkStatusArray['mr'], $this->formTemplate);
        $this->formTemplate = str_replace('{mrDisabled}', '', $this->formTemplate);

        $this->formTemplate = str_replace('{mrsChecked}', $this->checkStatusArray['mrs'], $this->formTemplate);
        $this->formTemplate = str_replace('{mrsDisabled}', '', $this->formTemplate);

        $this->formTemplate = str_replace('{msChecked}', $this->checkStatusArray['ms'], $this->formTemplate);
        $this->formTemplate = str_replace('{msDisabled}', '', $this->formTemplate);

        $this->formTemplate = str_replace('{noneChecked}', $this->checkStatusArray['none'], $this->formTemplate);
        $this->formTemplate = str_replace('{noneDisabled}', '', $this->formTemplate);
    }

    /** Ajout des classes sur les élèments servant à l'affichage des messages d'erreur
     */
    private function validityStatusConverter(): void
    {
        $this->validityArray['lastname'] = ($this->checksArray['lastname'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['firstname'] = ($this->checksArray['firstname'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['tel'] = ($this->checksArray['tel'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['mail'] = ($this->checksArray['mail'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['webpage'] = ($this->checksArray['webpage'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['doctolibpage'] = ($this->checksArray['doctolibpage'] == true) ? 'is-valid' : 'is-invalid';
    }

    /** Configuration des champs de prénom et de nom
     */
    private function namesSetup(): void
    {
        // Nom de famille
        $this->formTemplate = str_replace('{lastnameValidity}', $this->validityArray['lastname'], $this->formTemplate);
        $this->formTemplate = str_replace('{lastnameValue}', $this->docData['lastName'], $this->formTemplate);
        $this->formTemplate = str_replace('{lastNameReadOnly}', '', $this->formTemplate);

        // Prénom
        $this->formTemplate = str_replace('{firstnameValidity}', $this->validityArray['firstname'], $this->formTemplate);
        $this->formTemplate = str_replace('{firstnameValue}', $this->docData['firstName'], $this->formTemplate);
        $this->formTemplate = str_replace('{fistNameReadOnly}', '', $this->formTemplate);
    }

    /** Configuration des champs de téléphone et de mail
     */
    private function telAndMailSetup(): void
    {
        // Tel
        $this->formTemplate = str_replace('{telValidity}', $this->validityArray['tel'], $this->formTemplate);
        $this->formTemplate = str_replace('{telValue}', $this->docData['tel'], $this->formTemplate);
        $this->formTemplate = str_replace('{telReadOnly}', '', $this->formTemplate);

        // Mail
        $this->formTemplate = str_replace('{mailValidity}', $this->validityArray['mail'], $this->formTemplate);
        $this->formTemplate = str_replace('{mailValue}', $this->docData['mail'], $this->formTemplate);
        $this->formTemplate = str_replace('{mailReadOnly}', '', $this->formTemplate);
    }

    /** Configuration des champs de page perso et Doctolib
     */
    private function webLinksSetup(): void
    {
        // Site web perso
        $this->formTemplate = str_replace('{webpageValidity}', $this->validityArray['webpage'], $this->formTemplate);
        $this->formTemplate = str_replace('{webpageValue}', $this->docData['webPage'], $this->formTemplate);
        $this->formTemplate = str_replace('{webpageReadOnly}', '', $this->formTemplate);

        // Page Docotlib
        $this->formTemplate = str_replace('{doctolibpageValidity}', $this->validityArray['doctolibpage'], $this->formTemplate);
        $this->formTemplate = str_replace('{doctolibpageValue}', $this->docData['doctolibPage'], $this->formTemplate);
        $this->formTemplate = str_replace('{doctolibpageReadOnly}', '', $this->formTemplate);
    }

    /** Configuration du champ de commentaires
     */
    private function commentsSetup(): void
    {
        // Commentaires
        $this->formTemplate = str_replace('{commentContent}', $this->docData['comment'], $this->formTemplate);
        $this->formTemplate = str_replace('{commentReadOnly}', '', $this->formTemplate);
    }

    /** Configuration des boutons du form
     */
    private function formButtonsSetup(): void
    {
        // Bouton de submit
        $submitButtonTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/forms/formButtons/submitButton.html');
        $submitButtonTemplate = str_replace('{formSubmitButtonValue}', '', $submitButtonTemplate);
        $submitButtonTemplate = str_replace('{formSubmitButtonText}', 'Modifier', $submitButtonTemplate);

        // Bouton de reset
        $resetButtonTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/forms/formButtons/resetButton.html');

        // Bouton de suppression
        $deleteButtonTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/forms/formButtons/deleteButton.html');
        $deleteButtonTemplate = str_replace('{formDeleteButtonValue}', '', $deleteButtonTemplate);

        // Bouton d'annulation
        $cancelButtonTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/forms/formButtons/cancelButton.html');
        $cancelButtonTemplate = str_replace('{cancelButtonHref}', 'index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp', $cancelButtonTemplate);

        // Création et configuration du button box du forum
        $buttonBoxTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/forms/formButtons/formButtonBox.html');
        $buttonBoxTemplate = str_replace('{submitButton}', $submitButtonTemplate, $buttonBoxTemplate);
        $buttonBoxTemplate = str_replace('{resetButton}', $resetButtonTemplate, $buttonBoxTemplate);
        $buttonBoxTemplate = str_replace('{deleteButton}', '', $buttonBoxTemplate);
        $buttonBoxTemplate = str_replace('{cancelButton}', $cancelButtonTemplate, $buttonBoxTemplate);

        // Intégration du button box dans le form
        $this->formTemplate = str_replace('{docFormButtonBox}', $buttonBoxTemplate, $this->formTemplate);
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => $this->formTemplate,
            'speMedicModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
    }
}
