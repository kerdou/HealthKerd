import PwdFormChecks from './pwdFormChecks.js';
import _ from 'lodash';

export default class PwdFormBehaviour extends PwdFormChecks
{
    private pwdForm = document.getElementById('user_account_pwd_form') as HTMLFormElement;

    protected pwdInput = document.getElementById('pwd') as HTMLInputElement;
    protected confPwdInput = document.getElementById('confPwd') as HTMLInputElement;
    protected samePwdInput = document.getElementById('samePwd') as HTMLInputElement;
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
        this.pwdInput.addEventListener('input', _.debounce(this.formChecks.bind(this), 150));
        this.confPwdInput.addEventListener('input', _.debounce(this.formChecks.bind(this), 150));
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
        this.pwdInput.classList.remove('is-invalid');
        this.confPwdInput.classList.remove('is-invalid');
        this.samePwdInput.classList.remove('is-invalid');
        this.pwdForm.reset();
    }

    /** Comportement lors de l'appui sur le bouton de Submit
     */
    private submitForm(): void {
        let formHasIssues = this.formChecks();

        // Si aucun test ne renvoie false, on peut submit le form
        if (formHasIssues == false) {
            this.pwdForm.submit();
        }
    }

    /** Série de vérifications des champs du formulaire
     * @returns {boolean} Renvoie du statut de vérification du formulaire
     */
    private formChecks(): boolean {
        let pwdCheck = super.pwdCheck('pwd');
        let confPwdCheck = super.pwdCheck('confPwd');

        let formHasIssues = false;
        formHasIssues = super.samePwdCheck(pwdCheck, confPwdCheck);

        return formHasIssues;
    }
}
