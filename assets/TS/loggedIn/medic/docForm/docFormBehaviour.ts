import DocFormChecks from './docFormChecks.js';
import _ from 'lodash';

export default class DocFormBehaviour extends DocFormChecks
{
    private docForm = document.getElementById('doc_form_page') as HTMLFormElement;

    protected lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    protected firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    protected telInput = document.getElementById('tel') as HTMLInputElement;
    protected mailInput = document.getElementById('mail') as HTMLInputElement;
    protected webpageInput = document.getElementById('webpage') as HTMLInputElement;
    protected doctolibpageInput = document.getElementById('doctolibpage') as HTMLInputElement;

    private formResetButton = document.getElementById('formResetButton') as HTMLButtonElement;
    private formSubmitButton = document.getElementById('formSubmitButton') as HTMLButtonElement;

    constructor() {
        super();
        this.fieldInputsFocusOutEventListeners();
        this.telInputKeyDownEventListener();
        this.formButtonsEventListeners();
    }

    /** Listeners pour les champs du form quand ils perdet le focus, sert à lancer la vérification des champs
     */
    private fieldInputsFocusOutEventListeners(): void {
        this.lastnameInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.firstnameInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.telInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.mailInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.webpageInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.doctolibpageInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
    }

    /** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide
    */
    private focusOutRecheck(): void {
        this.formChecks();
    }

    /** Listener des touches du clavier pour le champ de téléphone
     */
    private telInputKeyDownEventListener(): void {
        this.telInput.addEventListener('keydown', this.telKeyCheck);
    }

    /** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone
     * @param {KeyboardEvent} event
     */
    private telKeyCheck(event: KeyboardEvent): void {
        if (
            !(event.key >= '0' && event.key <= '9') &&
            event.key != '+' &&
            event.code != 'NumpadAdd' &&
            event.key != '.' &&
            event.code != 'NumpadDecimal' &&
            event.code != 'Backspace' &&
            event.code != 'Delete' &&
            event.code != 'ArrowLeft' &&
            event.code != 'ArrowRight' &&
            event.code != 'Tab'
        ) {
            event.preventDefault();
        } else if (event.code == 'Space') {
            event.preventDefault();
        }
    }

    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    private formButtonsEventListeners(): void {
        if (this.formResetButton != null) {
            this.formResetButton.addEventListener('click', this.resetForm.bind(this));
        }
        this.formSubmitButton.addEventListener('click', this.submitForm.bind(this));
    }

    /** Reset du form et des classes des champs inputs
     */
    private resetForm(): void {
        this.lastnameInput.classList.remove('is-invalid');
        this.firstnameInput.classList.remove('is-invalid');
        this.telInput.classList.remove('is-invalid');
        this.mailInput.classList.remove('is-invalid');
        this.webpageInput.classList.remove('is-invalid');
        this.doctolibpageInput.classList.remove('is-invalid');
        this.docForm.reset();
    }

    /** Comportement lors de l'appui sur le bouton de Submit
     */
    private submitForm(): void {
        let formValidityResult = {};
        formValidityResult = this.formChecks();
        let formHasIssues = _.includes(formValidityResult, false);

        // Si aucun test ne renvoie false, on peut submit le form
        if (!formHasIssues) {
            this.docForm.submit();
        }
    }

    /** Série de vérifications des champs du formulaire
     * @returns {object} Renvoie du statut de vérification du formulaire
     */
    private formChecks(): object {
        let formValidity = {
            lastNameCheck: false,
            firstNameCheck: false,
            telCheck: false,
            mailCheck: false,
            webPageCheck: false,
            doctolibPageCheck: false
        };

        formValidity.lastNameCheck = super.lastNameCheck();
        formValidity.firstNameCheck = super.firstNameCheck();
        formValidity.telCheck = super.telCheck();
        formValidity.mailCheck = super.mailCheck();
        formValidity.webPageCheck = super.webPageCheck();
        formValidity.doctolibPageCheck = super.doctolibPageCheck();

        return formValidity;
    }
}
