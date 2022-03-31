<?php

namespace HealthKerd\View\userAccount\userForm;

class FailedEditFormBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $userData = array();
    private array $checksArray = array();
    private array $transformList = array();

    private array $pageSettingsList = array();
    private array $contentSettingsList = array();
    protected string $pageContent = '';

    private array $mainFieldsSettingsList = array();
    private string $mainFieldsContent = '';

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
            "pageTitle" => 'Compte utilisateur',
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
     * @param array $userData       Données partielles du user, le reste est dans $_SESSION
     * @param array $checksArray    Liste des erreurs du formulaire
     */
    public function buildOrder(array $userData, array $checksArray): void
    {
        $this->userData = $userData;
        $this->checksArray = $checksArray;

        $this->dataTransfo();

        $this->mainFieldsSettingsList();
        $this->mainFieldsStringReplace();

        $this->buttonBoxSettingsList();
        $this->buttonBoxStringReplace();

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /**
     */
    private function dataTransfo(): void
    {
        $this->genderTransfo($this->userData['gender']);
        $this->validityStatusConverter();
    }

    /**
     * @param string $gender
     */
    private function genderTransfo(string $gender): void
    {
        $this->transformList['mChecked'] = '';
        $this->transformList['fChecked'] = '';
        $this->transformList['uChecked'] = '';

        switch ($gender) {
            case 'M':
                $this->transformList['mChecked'] = 'checked';
                break;

            case 'F':
                $this->transformList['fChecked'] = 'checked';
                break;

            case 'U':
                $this->transformList['uChecked'] = 'checked';
                break;
        }
    }


    /** Ajout des classes sur les élèments servant à l'affichage des messages d'erreur
     */
    private function validityStatusConverter(): void
    {
        $this->validityArray['lastname'] = ($this->checksArray['lastname'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['firstname'] = ($this->checksArray['firstname'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['birthDate'] = ($this->checksArray['birthDate'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['login'] = ($this->checksArray['login'] == true) ? 'is-valid' : 'is-invalid';
        $this->validityArray['mail'] = ($this->checksArray['mail'] == true) ? 'is-valid' : 'is-invalid';
    }

    /** Liste des paramètres des mainFields
     */
    private function mainFieldsSettingsList(): void
    {
        $this->mainFieldsSettingsList = array(
            'mainFieldsTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/sections/mainFields.html'),

            'lastnameValidity' => $this->validityArray['lastname'],
            'lastnameValue' => $this->userData['lastname'],
            'lastNameReadOnly' => '',

            'firstnameValidity' => $this->validityArray['firstname'],
            'firstnameValue' => $this->userData['firstname'],
            'fistNameReadOnly' => '',

            'mChecked' => $this->transformList['mChecked'],
            'mDisabled' => '',
            'fChecked' => $this->transformList['fChecked'],
            'fDisabled' => '',
            'uChecked' => $this->transformList['uChecked'],
            'uDisabled' => '',

            'birthDateValidity' => $this->validityArray['birthDate'],
            'birthDateValue' => $this->userData['birthDate'],
            'birthDateReadOnly' => '',

            'loginValidity' => $this->validityArray['login'],
            'loginValue' => $this->userData['login'],
            'loginReadOnly' => '',

            'mailValidity' => $this->validityArray['mail'],
            'mailValue' => $this->userData['mail'],
            'mailReadOnly' => ''
        );
    }

    /** String replace pour les mainFields
     */
    private function mainFieldsStringReplace(): void
    {
        $this->mainFieldsContent = $this->mainFieldsSettingsList['mainFieldsTemplate'];

        $this->mainFieldsContent = str_replace('{lastnameValidity}', $this->mainFieldsSettingsList['lastnameValidity'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{lastnameValue}', $this->mainFieldsSettingsList['lastnameValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{lastNameReadOnly}', $this->mainFieldsSettingsList['lastNameReadOnly'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{firstnameValidity}', $this->mainFieldsSettingsList['firstnameValidity'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{firstnameValue}', $this->mainFieldsSettingsList['firstnameValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{fistNameReadOnly}', $this->mainFieldsSettingsList['fistNameReadOnly'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{mChecked}', $this->mainFieldsSettingsList['mChecked'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{mDisabled}', $this->mainFieldsSettingsList['mDisabled'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{fChecked}', $this->mainFieldsSettingsList['fChecked'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{fDisabled}', $this->mainFieldsSettingsList['fDisabled'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{uChecked}', $this->mainFieldsSettingsList['uChecked'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{uDisabled}', $this->mainFieldsSettingsList['uDisabled'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{birthDateValidity}', $this->mainFieldsSettingsList['birthDateValidity'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{birthDateValue}', $this->mainFieldsSettingsList['birthDateValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{birthDateReadOnly}', $this->mainFieldsSettingsList['birthDateReadOnly'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{loginValidity}', $this->mainFieldsSettingsList['loginValidity'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{loginValue}', $this->mainFieldsSettingsList['loginValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{loginReadOnly}', $this->mainFieldsSettingsList['loginReadOnly'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{mailValidity}', $this->mainFieldsSettingsList['mailValidity'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{mailValue}', $this->mainFieldsSettingsList['mailValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{mailReadOnly}', $this->mainFieldsSettingsList['mailReadOnly'], $this->mainFieldsContent);
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
            'deleteButtonTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/formButtons/deleteButton.html'),
            'deleteButtonHRef' => 'index.php?controller=userAccount&action=showDelForm',
            'cancelButtonTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/formButtons/cancelButton.html'),
            'cancelButtonHRef' => 'index.php?controller=userAccount&action=showAccountPage'
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
            'userFormId' => 'user_account_form',
            'formAction' => 'index.php?controller=userAccountPost&action=accountModif',
            'formTitle' => 'Modification du compte utilisateur',
            'mainFields' => $this->mainFieldsContent,
            'pwdSection' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/sections/pwdChangeButton.html'),
            'formButtonBox' => $this->buttonBoxContent,
            'speMedicModal' => ''
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
    }
}
