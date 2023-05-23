import * as pwdFormChecks from './pwdFormChecks.js';
import _ from 'lodash';

export default function pwdFormBehaviour()
{
    const pwdForm = document.getElementById('user_account_pwd_form') as HTMLFormElement;

    const pwdInput = document.getElementById('pwd') as HTMLInputElement;
    const confPwdInput = document.getElementById('confPwd') as HTMLInputElement;
    const samePwdInput = document.getElementById('samePwd') as HTMLInputElement;
    const formResetButton = document.getElementById('formResetButton') as HTMLButtonElement;
    const formSubmitButton = document.getElementById('formSubmitButton') as HTMLButtonElement;


    fieldInputsEventListeners();
    formButtonsEventListeners();


    /** Listeners pour les champs du form quand on change leur contenu avec un debounce, sert à lancer la vérification des champs
     */
    function fieldInputsEventListeners() {
        pwdInput.addEventListener('input', _.debounce(formChecks, 150));
        confPwdInput.addEventListener('input', _.debounce(formChecks, 150));
    }

    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    function formButtonsEventListeners() {
        formResetButton.addEventListener('click', resetForm);
        formSubmitButton.addEventListener('click', submitForm);
    }

    /** Reset du form et des classes des champs inputs
     */
    function resetForm() {
        pwdInput.classList.remove('is-invalid');
        confPwdInput.classList.remove('is-invalid');
        samePwdInput.classList.remove('is-invalid');
        pwdForm.reset();
    }

    /** Comportement lors de l'appui sur le bouton de Submit
     */
    function submitForm() {
        const formHasIssues = formChecks();

        // Si aucun test ne renvoie false, on peut submit le form
        if (formHasIssues == false) {
            pwdForm.submit();
        }
    }

    /** Série de vérifications des champs du formulaire
     * @returns {boolean} Renvoie du statut de vérification du formulaire
     */
    function formChecks(): boolean {
        const pwdCheck = pwdFormChecks.pwdCheck('pwd');
        const confPwdCheck = pwdFormChecks.pwdCheck('confPwd');

        let formHasIssues = false;
        formHasIssues = pwdFormChecks.samePwdCheck(pwdCheck, confPwdCheck);

        return formHasIssues;
    }
}
