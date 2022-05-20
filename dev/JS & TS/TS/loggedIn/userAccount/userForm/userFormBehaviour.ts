import UserFormChecks from './userFormChecks.js';
import _ from 'lodash';

export default class UserFormBehaviour extends UserFormChecks
{
    private userForm = document.getElementById('user_account_form') as HTMLFormElement;

    protected lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    protected firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    protected birthDateInput = document.getElementById('birthDate') as HTMLInputElement;
    protected loginInput = document.getElementById('login') as HTMLInputElement;
    protected mailInput = document.getElementById('mail') as HTMLInputElement;
    private formResetButton = document.getElementById('formResetButton') as HTMLButtonElement;
    private formSubmitButton = document.getElementById('formSubmitButton') as HTMLButtonElement;

    constructor() {
        super();
        this.fieldInputsEventListeners();
        this.formButtonsEventListeners();
    }

    /** Listeners pour les champs du form quand on change leur contenu avec un debounce, sert à lancer la vérification des champs
     */
    private fieldInputsEventListeners(): void {
        this.lastnameInput.addEventListener('input', _.debounce(this.formChecks.bind(this), 150));
        this.firstnameInput.addEventListener('input', _.debounce(this.formChecks.bind(this), 150));
        this.birthDateInput.addEventListener('input', _.debounce(this.formChecks.bind(this), 150));
        this.loginInput.addEventListener('input', _.debounce(this.formChecks.bind(this), 150));
        this.mailInput.addEventListener('input', _.debounce(this.formChecks.bind(this), 150));
    }

    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    private formButtonsEventListeners(): void {
        this.formResetButton.addEventListener('click', this.resetForm.bind(this));
        this.formSubmitButton.addEventListener('click', this.submitForm.bind(this));
    }

    /** Reset du form et des classes des champs inputs
     */
    private resetForm(): void {
        this.lastnameInput.classList.remove('is-invalid');
        this.firstnameInput.classList.remove('is-invalid');
        this.birthDateInput.classList.remove('is-invalid');
        this.loginInput.classList.remove('is-invalid');
        this.mailInput.classList.remove('is-invalid');
        this.userForm.reset();
    }

    /** Comportement lors de l'appui sur le bouton de Submit
     */
    private submitForm(): void {
        let formValidityResult = {};
        formValidityResult = this.formChecks();
        let formHasIssues = _.includes(formValidityResult, false);

        // Si aucun test ne renvoie false, on peut submit le form
        if (!formHasIssues) {
            this.userForm.submit();
        }
    }

    /** Série de vérifications des champs du formulaire
     * @returns {object} Renvoie du statut de vérification du formulaire
     */
    private formChecks(): object {
        let formValidity = {
            lastNameCheck: false,
            firstNameCheck: false,
            birthDateCheck: false,
            loginCheck: false,
            mailCheck: false
        };

        formValidity.lastNameCheck = super.lastNameCheck();
        formValidity.firstNameCheck = super.firstNameCheck();
        formValidity.birthDateCheck = super.birthDateCheck();
        formValidity.loginCheck = super.loginCheck();
        formValidity.mailCheck = super.mailCheck();

        return formValidity;
    }
}
