<?php

namespace HealthKerd\View\userAccount\userForm;

class FailedEditPwdFormBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $contentSettingsList = array();
    protected string $pageContent = '';

    private array $cleanedUpPost = array();
    private array $checksArray = array();

    private array $pwdFieldSettingsList = array();
    protected string $pwdFieldContent = '';

    private array $buttonBoxSettingsList = array();
    private string $buttonBoxContent = '';

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
            "pageTitle" => 'Modification de mot de passe',
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

    /** Recoit les données des futurs rendez-vous puis lance la construction du HTML de ce contenu
     */
    public function buildOrder(array $cleanedUpPost, array $checksArray): void
    {
        $this->cleanedUpPost = $cleanedUpPost;
        $this->checksArray = $checksArray;

        $this->pwdFieldSettingsList();
        $this->pwdFieldStringReplace();

        $this->validityStatusConverter();
        $this->validityStatusStringReplacer();

        $this->buttonBoxSettingsList();
        $this->buttonBoxStringReplace();

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /**
     *
     */
    private function pwdFieldSettingsList(): void
    {
        $this->pwdFieldSettingsList = array(
            'pwdSectionTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/sections/pwdFields.html'),
            'pwdValue' => $this->cleanedUpPost['pwd'],
            'confPwdValue' => $this->cleanedUpPost['confPwd']
        );
    }

    /**
     *
     */
    private function pwdFieldStringReplace(): void
    {
        $this->pwdFieldContent = $this->pwdFieldSettingsList['pwdSectionTemplate'];

        $this->pwdFieldContent = str_replace('{pwdValue}', $this->pwdFieldSettingsList['pwdValue'], $this->pwdFieldContent);
        $this->pwdFieldContent = str_replace('{confPwdValue}', $this->pwdFieldSettingsList['confPwdValue'], $this->pwdFieldContent);
    }

    /** Ajout des classes sur les élèments servant à l'affichage des messages d'erreur
     */
    private function validityStatusConverter(): void
    {
        $this->validityArray['pwd'] = ($this->checksArray['pwd']['status']['isValid'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['confPwd'] = ($this->checksArray['confPwd']['status']['isValid'] == true) ? 'is-valid' : 'is-invalid';

        if (($this->checksArray['pwd']['summary']['length'] > 0) && ($this->checksArray['confPwd']['summary']['length'] > 0)) {
            $this->validityArray['samePwd'] = ($this->checksArray['overall']['identical'] == true) ? 'is-valid' : 'is-invalid';
        } else {
            $this->validityArray['samePwd'] = '';
        }
    }

    /**
     *
     */
    private function validityStatusStringReplacer(): void
    {
        $this->pwdFieldContent = str_replace('{pwdValidity}', $this->validityArray['pwd'], $this->pwdFieldContent);
        $this->pwdFieldContent = str_replace('{confPwdValidity}', $this->validityArray['confPwd'], $this->pwdFieldContent);
        $this->pwdFieldContent = str_replace('{samePwdValidity}', $this->validityArray['samePwd'], $this->pwdFieldContent);
    }

    /**
     *
     */
    private function buttonBoxSettingsList(): void
    {
        $this->buttonBoxSettingsList = array(
            'buttonBoxTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/formButtons/formButtonBox.html'),
            'submitButtonTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/formButtons/submitButton.html'),
            'submitButtonValue' => '',
            'submitButtonText' => 'Modifier',
            'resetButtonTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/formButtons/resetButton.html'),
            'deleteButtonTemplate' => '',
            'deleteButtonHRef' => '',
            'cancelButtonTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/formButtons/cancelButton.html'),
            'cancelButtonHRef' => 'index.php?controller=userAccount&action=showEditForm'
        );
    }

    /**
     *
     */
    private function buttonBoxStringReplace(): void
    {
        $this->buttonBoxContent = $this->buttonBoxSettingsList['buttonBoxTemplate'];

        $this->buttonBoxContent = str_replace('{submitButton}', $this->buttonBoxSettingsList['submitButtonTemplate'], $this->buttonBoxContent);
        $this->buttonBoxContent = str_replace('{submitButtonValue}', $this->buttonBoxSettingsList['submitButtonValue'], $this->buttonBoxContent);
        $this->buttonBoxContent = str_replace('{submitButtonText}', $this->buttonBoxSettingsList['submitButtonText'], $this->buttonBoxContent);

        $this->buttonBoxContent = str_replace('{resetButton}', $this->buttonBoxSettingsList['resetButtonTemplate'], $this->buttonBoxContent);

        $this->buttonBoxContent = str_replace('{deleteButton}', $this->buttonBoxSettingsList['deleteButtonTemplate'], $this->buttonBoxContent);
        $this->buttonBoxContent = str_replace('{deleteButtonHRef}', $this->buttonBoxSettingsList['deleteButtonHRef'], $this->buttonBoxContent);

        $this->buttonBoxContent = str_replace('{cancelButton}', $this->buttonBoxSettingsList['cancelButtonTemplate'], $this->buttonBoxContent);
        $this->buttonBoxContent = str_replace('{cancelButtonHRef}', $this->buttonBoxSettingsList['cancelButtonHRef'], $this->buttonBoxContent);
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/userAccountForm.html'),
            'userFormId' => 'user_account_pwd_form',
            'formAction' => 'index.php?controller=userAccountPost&action=pwdModif',
            'formTitle' => 'Modification du mot de passe',
            'mainFields' => '',
            'pwdSection' => $this->pwdFieldContent,
            'formButtonBox' => $this->buttonBoxContent,
            'speMedicModal' => '',
            'docModifModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{userFormId}', $this->contentSettingsList['userFormId'], $this->pageContent);
        $this->pageContent = str_replace('{formAction}', $this->contentSettingsList['formAction'], $this->pageContent);
        $this->pageContent = str_replace('{formTitle}', $this->contentSettingsList['formTitle'], $this->pageContent);
        $this->pageContent = str_replace('{mainFields}', $this->contentSettingsList['mainFields'], $this->pageContent);
        $this->pageContent = str_replace('{pwdSection}', $this->contentSettingsList['pwdSection'], $this->pageContent);
        $this->pageContent = str_replace('{formButtonBox}', $this->contentSettingsList['formButtonBox'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
        $this->pageContent = str_replace('{docModifModal}', $this->contentSettingsList['docModifModal'], $this->pageContent);
    }
}
