import * as pwdFormChecks from './pwdFormChecks.js';
import _ from 'lodash';
export default function pwdFormBehaviour() {
    var pwdForm = document.getElementById('user_account_pwd_form');
    var pwdInput = document.getElementById('pwd');
    var confPwdInput = document.getElementById('confPwd');
    var samePwdInput = document.getElementById('samePwd');
    var formResetButton = document.getElementById('formResetButton');
    var formSubmitButton = document.getElementById('formSubmitButton');
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
        var formHasIssues = formChecks();
        // Si aucun test ne renvoie false, on peut submit le form
        if (formHasIssues == false) {
            pwdForm.submit();
        }
    }
    /** Série de vérifications des champs du formulaire
     * @returns {boolean} Renvoie du statut de vérification du formulaire
     */
    function formChecks() {
        var pwdCheck = pwdFormChecks.pwdCheck('pwd');
        var confPwdCheck = pwdFormChecks.pwdCheck('confPwd');
        var formHasIssues = false;
        formHasIssues = pwdFormChecks.samePwdCheck(pwdCheck, confPwdCheck);
        return formHasIssues;
    }
}
