<?php

namespace HealthKerd\View\medic\doc\oneDoc;

/** Construction puis affichage de la page d'un seul docteur
 * Certains blocs ne s'affichent que s'ils contiennent des données
 */
class OneDocPageBuilder extends \HealthKerd\View\common\ViewInChief
{
    private array $pageSettingsList = array();
    private array $docDataArray = array();

    private string $modifButtonHTML = '';
    private string $speMedicBadgesHTML = '';
    private string $contactContentHTML = '';
    private string $commentPortionHTML = '';
    private string $medicEventsReportHTML = '';
    private string $docOfficeCardsHTML = '';
    private string $docModifModalHTML = '';

    public function __construct()
    {
        parent::__construct();
        $this->pageElementsSettingsList();
        $this->pageElementsStringReplace();
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
            "pageTitle" => 'Informations liées à un professionnel de santé',
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

    /** Génération de tous les blocs HTML et stockage dans $builtContentArray avant affichage
     * @param array $docDataArray   Données du docteur concerné
    */
    public function buildOrder(array $docDataArray): void
    {
        $this->docDataArray = $docDataArray;

        $this->modifButtonHTML = $this->modifButtonBuilder();
        $this->speMedicBadgesHTML = $this->speMedicBadgesBuilder();
        $this->contactContentHTML = $this->contactContentBuilder();
        $this->commentPortionHTML = $this->commentPortionBuilder();
        $this->medicEventsReportHTML = $this->medicEventsReportBuilder();
        $this->docOfficeCardsHTML =  $this->docOfficeCardsBuilder();
        $this->docModifModalHTML = $this->docModifModalBuilder();

        $this->contentElementsSettingsList();
        $this->contentElementsStringReplace();

        $this->pageDisplay();
    }

    /** Ajout du bouton de modification du docteur s'il est modifiable
     * @return string       HTML du bouton de modification
     */
    private function modifButtonBuilder(): string
    {
        if ($this->docDataArray['isLocked'] == 0) {
            $modifButtonHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/modifButton.html');
        } else {
            $modifButtonHTML = '';
        }

        return $modifButtonHTML;
    }

    /** Construction des badges de spécialités médicales du docteur
     * @return string       HTML des badges de spécialités médicales
     */
    private function speMedicBadgesBuilder(): string
    {
        $speMedicBadgesHTML = '';

        foreach ($this->docDataArray['speMedicList'] as $value) {
            //var_dump($value);
            $badgeTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/docSpeMedic/docSpeMedic.html');
            $badgeTemplate = str_replace('{speMedicName}', $value['name'], $badgeTemplate);
            $speMedicBadgesHTML .= $badgeTemplate;
        }

        return $speMedicBadgesHTML;
    }

    /** Construction des éléments de contact du docteur
     * * Dépend de la présence d'un numéro de téléphone
     * * Dépend de la présence d'un mail
     * * Dépend de la présence d'une page web personnelle
     * * Dépend de la présence d'une page sur Doctolib
     * @return string       HTML de l'ensemble des informations de contact
     */
    private function contactContentBuilder(): string
    {
        if (
            $this->docDataArray['tel'] == '' &&
            $this->docDataArray['mail'] == '' &&
            $this->docDataArray['webPage'] == '' &&
            $this->docDataArray['doctolibPage'] == ''
        ) {
            $contactContentHTML = '';
        } else {
            $contactContentHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/contactContent/contactContent.html');

            // S'il y a des données de tel ou de mail, elles iront dans {phoneAndMailContent}
            if ($this->docDataArray['tel'] == '' && $this->docDataArray['mail'] == '') {
                $phoneAndMailContentHTML = '';
            } else {
                $phoneAndMailContentHTML = $this->phoneAndMailContentBuilder();
            }
            $contactContentHTML = str_replace('{phoneAndMailContent}', $phoneAndMailContentHTML, $contactContentHTML);

            // S'il y a des données de page web perso ou DoctoLib, elles iront dans {websitesContent}
            if ($this->docDataArray['webPage'] == '' && $this->docDataArray['doctolibPage'] == '') {
                $websitesContentHTML = '';
            } else {
                $websitesContentHTML = $this->websitesContentBuilder();
            }
            $contactContentHTML = str_replace('{websitesContent}', $websitesContentHTML, $contactContentHTML);
        }

        return $contactContentHTML;
    }

    /** Construction des éléments de tel et/ou mail s'il y en a
     * @param string    HTML des éléments de tel et/ou mail
     */
    private function phoneAndMailContentBuilder(): string
    {
        $phoneAndMailContentHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/contactContent/phoneAndMailContent/phoneAndMailContent.html');

        // éléments de tel s'il y en a
        if ($this->docDataArray['tel'] == '') {
            $telPortionHTML = '';
        } else {
            $telPortionHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/contactContent/phoneAndMailContent/telPortion.html');
            $telPortionHTML = str_replace('{tel}', $this->docDataArray['tel'], $telPortionHTML);
        }
        $phoneAndMailContentHTML = str_replace('{telPortion}', $telPortionHTML, $phoneAndMailContentHTML);

        // éléments de mail s'il y en a
        if ($this->docDataArray['mail'] == '') {
            $mailPortionHTML = '';
        } else {
            $mailPortionHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/contactContent/phoneAndMailContent/mailPortion.html');
            $mailPortionHTML = str_replace('{mail}', $this->docDataArray['mail'], $mailPortionHTML);
        }
        $phoneAndMailContentHTML = str_replace('{mailPortion}', $mailPortionHTML, $phoneAndMailContentHTML);

        return $phoneAndMailContentHTML;
    }

    /** Construction des éléments de page web perso et/ou de page Doctolib s'il y en a
     * @param string    HTML de page web perso et/ou de page Doctolib
     */
    private function websitesContentBuilder(): string
    {
        $websitesContentHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/contactContent/websitesContent/websitesContent.html');

        // éléments de page web perso s'il y en a
        if ($this->docDataArray['webPage'] == '') {
            $webPageHTML = '';
        } else {
            $webPageHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/contactContent/websitesContent/persoWebPagePortion.html');
            $webPageHTML = str_replace('{webPage}', $this->docDataArray['webPage'], $webPageHTML);
        }
        $websitesContentHTML = str_replace('{persoWebPagePortion}', $webPageHTML, $websitesContentHTML);

        // éléments de page Doctolib s'il y en a
        if ($this->docDataArray['doctolibPage'] == '') {
            $doctolibPageHTML = '';
        } else {
            $doctolibPageHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/contactContent/websitesContent/doctoLibPagePortion.html');
            $doctolibPageHTML = str_replace('{doctolibPage}', $this->docDataArray['doctolibPage'], $doctolibPageHTML);
        }
        $websitesContentHTML = str_replace('{doctoLibPagePortion}', $doctolibPageHTML, $websitesContentHTML);

        return $websitesContentHTML;
    }

    /** Eléments de commentaires concernant le docteur
     * @return string       HTML des commentaires
     */
    private function commentPortionBuilder(): string
    {
        if ($this->docDataArray['comment'] == '') {
            $commentPortionHTML = '';
        } else {
            $commentPortionHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/commentPortion.html');
            $commentPortionHTML = str_replace('{commentContent}', $this->docDataArray['comment'], $commentPortionHTML);
        }

        return $commentPortionHTML;
    }

    /** Eléments de rapport de consultations médicales
     * @return string       HTML des commentaires
     */
    private function medicEventsReportBuilder(): string
    {
        $medicEventsReportHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/medicEventReport.html');

        $medicEventsReportHTML = str_replace('{pastEventsQty}', $this->docDataArray['medicEvent']['qty']['past'], $medicEventsReportHTML);
        $medicEventsReportHTML = str_replace('{comingEventsQty}', $this->docDataArray['medicEvent']['qty']['coming'], $medicEventsReportHTML);
        $medicEventsReportHTML = str_replace('{totalEventsQty}', $this->docDataArray['medicEvent']['qty']['total'], $medicEventsReportHTML);

        $medicEventsReportHTML = str_replace('{firstEventDate}', $this->docDataArray['medicEvent']['dates']['first'], $medicEventsReportHTML);
        $medicEventsReportHTML = str_replace('{lastEventDate}', $this->docDataArray['medicEvent']['dates']['last'], $medicEventsReportHTML);
        $medicEventsReportHTML = str_replace('{nextEventDate}', $this->docDataArray['medicEvent']['dates']['next'], $medicEventsReportHTML);

        return $medicEventsReportHTML;
    }

    /** Construction des cards de cabinets médicaux
     * @return string       HTML des cards de cabinets médicaux
     */
    private function docOfficeCardsBuilder(): string
    {
        $docOfficeCardsTemplate = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/docOfficeCard.html');
        $docOfficeCardsHTML = '';

        if (sizeof($this->docDataArray['docOfficeList']) == 0) {
            $docOfficeCardsHTML = 'Aucun cabinet médical rattaché';
        } else {
            foreach ($this->docDataArray['docOfficeList'] as $value) {
                $docOfficeTemp = $docOfficeCardsTemplate;

                $docOfficeTemp = str_replace('{docOfficeID}', $value['docOfficeID'], $docOfficeTemp);
                $docOfficeTemp = str_replace('{name}', $value['name'], $docOfficeTemp);
                $docOfficeTemp = str_replace('{cityName}', $value['cityName'], $docOfficeTemp);

                $docOfficeCardsHTML .= $docOfficeTemp;
            }
        }

        return $docOfficeCardsHTML;
    }

    /**
     *
     */
    private function docModifModalBuilder(): string
    {
        if ($this->docDataArray['isLocked'] == 0) {
            $docModifModalHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/modals/docModifModal.html');
        } else {
            $docModifModalHTML = '';
        }

        return $docModifModalHTML;
    }


    /** Liste des contenus spécifiques à cette page
     */
    private function contentElementsSettingsList(): void
    {
        $this->contentSettingsList = array(
            'mainContent' => file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/doc/oneDoc/oneDoc.html'),
            'modifButton' => $this->modifButtonHTML,
            'docFullNameSentence' => $this->docDataArray['fullNameSentence'],
            'speMedicBadges' => $this->speMedicBadgesHTML,
            'contactContent' => $this->contactContentHTML,
            'commentPortion' => $this->commentPortionHTML,
            'medicEventsReport' => $this->medicEventsReportHTML,
            'docOfficeCards' => $this->docOfficeCardsHTML,
            'speMedicModal' => '',
            'docModifModal' => $this->docModifModalHTML,
            'docID' => $this->docDataArray['docID']
        );
    }

    /** Application des contenus spécifiques à cette page
     */
    private function contentElementsStringReplace(): void
    {
        $this->pageContent = str_replace('{mainContent}', $this->contentSettingsList['mainContent'], $this->pageContent);
        $this->pageContent = str_replace('{docFullNameSentence}', $this->contentSettingsList['docFullNameSentence'], $this->pageContent);
        $this->pageContent = str_replace('{modifButton}', $this->contentSettingsList['modifButton'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicBadges}', $this->contentSettingsList['speMedicBadges'], $this->pageContent);
        $this->pageContent = str_replace('{contactContent}', $this->contentSettingsList['contactContent'], $this->pageContent);
        $this->pageContent = str_replace('{commentPortion}', $this->contentSettingsList['commentPortion'], $this->pageContent);
        $this->pageContent = str_replace('{medicEventsReport}', $this->contentSettingsList['medicEventsReport'], $this->pageContent);
        $this->pageContent = str_replace('{docOfficeCards}', $this->contentSettingsList['docOfficeCards'], $this->pageContent);
        $this->pageContent = str_replace('{speMedicModal}', $this->contentSettingsList['speMedicModal'], $this->pageContent);
        $this->pageContent = str_replace('{docModifModal}', $this->contentSettingsList['docModifModal'], $this->pageContent);
        $this->pageContent = str_replace('{docID}', $this->contentSettingsList['docID'], $this->pageContent);
    }
}
