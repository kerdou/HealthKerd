<?php

namespace HealthKerd\View\userAccount\userPage;

class UserAccountPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $contentSettingsList = array();
    private array $userData = array();
    private array $transformList = array();

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
            "pageTitle" => 'Accueil',
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
     * @param array $userData     Données partielles du user, le reste est dans $_SESSION
     */
    public function buildOrder(array $userData): void
    {
        $this->dataTransfo($userData);

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /**
     * @param array $userData
     */
    private function dataTransfo(array $userData): void
    {
        $this->userData = $userData;

        $this->genderTransfo($userData['gender']);
        $this->birthDateTransfo($userData['birthDate']);
        $this->ageCalc();
        $this->accountCreationDateTransfo($userData['accountCreationDate']);
        $this->isAdminDivCreation($_SESSION['isAdmin']);
        $this->modifButtonCreation($userData['modifLocked']);
    }

    /**
     * @param string $gender
     */
    private function genderTransfo(string $gender): void
    {
        switch ($gender) {
            case 'M':
                $this->transformList['gender'] = 'Masculin';
                break;

            case 'F':
                $this->transformList['gender'] = 'Féminin';
                break;

            case 'U':
                $this->transformList['gender'] = 'Indéfini';
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

    /**
     */
    private function ageCalc(): void
    {
        $diffObj = date_diff($this->transformList['birthDateObj'], $_ENV['DATEANDTIME']['nowDate']['nowTimeObj']);
        $this->transformList['age'] = $diffObj->format('%y ans');
    }

    /**
     * @param string $birthDate
     */
    private function accountCreationDateTransfo(string $accountCreationDate): void
    {
        $accountCreationDateObj = date_create_from_format('Y-m-d', $accountCreationDate, $_ENV['DATEANDTIME']['timezoneObj']);
        $accountCreationDateTimestamp = date_timestamp_get($accountCreationDateObj);

        $dateFormatObj = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
        $this->transformList['accountCreationDate'] = $dateFormatObj->format($accountCreationDateTimestamp);
    }

    /**
     */
    private function isAdminDivCreation(int $isAdmin): void
    {
        if ($isAdmin == 1) {
            $this->transformList['isAdminDiv'] = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userPage/isAdminDiv.html');
        } else {
            $this->transformList['isAdminDiv'] = '';
        }
    }

    /**
     */
    private function modifButtonCreation(int $modifLocked): void
    {
        if ($modifLocked == 1) {
            $this->transformList['modifButton'] = '';
        } else {
            $this->transformList['modifButton'] = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userPage/modifButton.html');
        }
    }

    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/userAccount/userPage/userAccountPage.html'),
            'userFullName' => $this->pageSettingsList['userFullName'],
            'gender' => $this->transformList['gender'],
            'age' => $this->transformList['age'],
            'birthDate' => $this->transformList['birthDate'],
            'accountCreationDate' => $this->transformList['accountCreationDate'],
            'userLogin' => $this->userData['userLogin'],
            'email' => $this->userData['email'],
            'isAdminDiv' => $this->transformList['isAdminDiv'],
            'modifButton' => $this->transformList['modifButton'],
            'speMedicModal' => ''
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{userFullName}', $this->contentSettingsList['userFullName'], $this->pageContent);
        $this->pageContent = str_replace('{gender}', $this->contentSettingsList['gender'], $this->pageContent);
        $this->pageContent = str_replace('{age}', $this->contentSettingsList['age'], $this->pageContent);
        $this->pageContent = str_replace('{birthDate}', $this->contentSettingsList['birthDate'], $this->pageContent);
        $this->pageContent = str_replace('{accountCreationDate}', $this->contentSettingsList['accountCreationDate'], $this->pageContent);
        $this->pageContent = str_replace('{userLogin}', $this->contentSettingsList['userLogin'], $this->pageContent);
        $this->pageContent = str_replace('{email}', $this->contentSettingsList['email'], $this->pageContent);
        $this->pageContent = str_replace('{isAdminDiv}', $this->contentSettingsList['isAdminDiv'], $this->pageContent);
        $this->pageContent = str_replace('{modifButton}', $this->contentSettingsList['modifButton'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
    }
}
