<?php

namespace HealthKerd\View\userAccount\userForm;

class EditFormBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $userData = array();
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
            'headContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/globalLayout/head.html'),
            "pageTitle" => 'Compte utilisateur',
            'headerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/globalLayout/header.html'),
            'mainContainer' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/mainContainer.html'),
            'sidebarMenuUlContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/sidebarMenuUlContent.html'),
            'userFullName' => $_SESSION['firstName'] . ' ' . $_SESSION['lastName'],
            'scrollUpButton' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/loggedGlobal/scrollUpArrow.html'),
            'footerContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/globalLayout/footer.html'),
            'BodyBottomDeclarations' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/globalLayout/BodyBottomDeclarations.html')
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
     * @param array $userData     Données partielles du user, le reste est dans $_SESSION
     */
    public function buildOrder(array $userData): void
    {
        $this->userData = $userData;

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
        $this->birthDateTransfo($this->userData['birthDate']);
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

    /**
     * @param string $birthDate
     */
    private function birthDateTransfo(string $birthDate): void
    {
        $this->transformList['birthDateObj'] = date_create_from_format('Y-m-d', $birthDate, $_ENV['DATEANDTIME']['timezoneObj']);
        $birthDateTimestamp = date_timestamp_get($this->transformList['birthDateObj']);

        $dateFormatObj = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
        $this->transformList['birthDate'] = $dateFormatObj->format($birthDateTimestamp);
    }

    /** Liste des paramètres des mainFields
     */
    private function mainFieldsSettingsList(): void
    {
        $this->mainFieldsSettingsList = array(
            'mainFieldsTemplate' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/sections/mainFields.html'),

            'lastnameValue' => $_SESSION['lastName'],
            'lastNameReadOnly' => '',

            'firstnameValue' => $_SESSION['firstName'],
            'fistNameReadOnly' => '',

            'mChecked' => $this->transformList['mChecked'],
            'mDisabled' => '',
            'fChecked' => $this->transformList['fChecked'],
            'fDisabled' => '',
            'uChecked' => $this->transformList['uChecked'],
            'uDisabled' => '',

            'birthDateValue' => $this->transformList['birthDate'],
            'birthDateReadOnly' => '',

            'loginValue' => $this->userData['userLogin'],
            'loginReadOnly' => '',

            'mailValue' => $this->userData['email'],
            'mailReadOnly' => ''
        );
    }

    /** String replace pour les mainFields
     */
    private function mainFieldsStringReplace(): void
    {
        $this->mainFieldsContent = $this->mainFieldsSettingsList['mainFieldsTemplate'];

        $this->mainFieldsContent = str_replace('{lastnameValue}', $this->mainFieldsSettingsList['lastnameValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{lastNameReadOnly}', $this->mainFieldsSettingsList['lastNameReadOnly'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{firstnameValue}', $this->mainFieldsSettingsList['firstnameValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{fistNameReadOnly}', $this->mainFieldsSettingsList['fistNameReadOnly'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{mChecked}', $this->mainFieldsSettingsList['mChecked'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{mDisabled}', $this->mainFieldsSettingsList['mDisabled'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{fChecked}', $this->mainFieldsSettingsList['fChecked'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{fDisabled}', $this->mainFieldsSettingsList['fDisabled'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{uChecked}', $this->mainFieldsSettingsList['uChecked'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{uDisabled}', $this->mainFieldsSettingsList['uDisabled'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{birthDateValue}', $this->mainFieldsSettingsList['birthDateValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{birthDateReadOnly}', $this->mainFieldsSettingsList['birthDateReadOnly'], $this->mainFieldsContent);

        $this->mainFieldsContent = str_replace('{loginValue}', $this->mainFieldsSettingsList['loginValue'], $this->mainFieldsContent);
        $this->mainFieldsContent = str_replace('{loginReadOnly}', $this->mainFieldsSettingsList['loginReadOnly'], $this->mainFieldsContent);

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
            'formAction' => 'accountModif',
            'formTitle' => 'Modification du compte utilisateur',
            'mainFields' => $this->mainFieldsContent,
            'pwdSection' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userForm/sections/pwdChangeButton.html'),
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
