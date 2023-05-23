import * as userFormChecks from './userFormChecks.js';
import _ from 'lodash';
export default function userFormBehaviour() {
    var userForm = document.getElementById('user_account_form');
    var lastnameInput = document.getElementById('lastname');
    var firstnameInput = document.getElementById('firstname');
    var birthDateInput = document.getElementById('birthDate');
    var loginInput = document.getElementById('login');
    var mailInput = document.getElementById('mail');
    var formResetButton = document.getElementById('formResetButton');
    var formSubmitButton = document.getElementById('formSubmitButton');
    fieldInputsEventListeners();
    formButtonsEventListeners();
    /** Listeners pour les champs du form quand on change leur contenu avec un debounce, sert à lancer la vérification des champs
     */
    function fieldInputsEventListeners() {
        lastnameInput.addEventListener('input', _.debounce(formChecks, 150));
        firstnameInput.addEventListener('input', _.debounce(formChecks, 150));
        birthDateInput.addEventListener('input', _.debounce(formChecks, 150));
        loginInput.addEventListener('input', _.debounce(formChecks, 150));
        mailInput.addEventListener('input', _.debounce(formChecks, 150));
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
        lastnameInput.classList.remove('is-invalid');
        firstnameInput.classList.remove('is-invalid');
        birthDateInput.classList.remove('is-invalid');
        loginInput.classList.remove('is-invalid');
        mailInput.classList.remove('is-invalid');
        userForm.reset();
    }
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    function submitForm() {
        var formValidityResult = {};
        formValidityResult = formChecks();
        var formHasIssues = _.includes(formValidityResult, false);
        // Si aucun test ne renvoie false, on peut submit le form
        if (!formHasIssues) {
            userForm.submit();
        }
    }
    /** Série de vérifications des champs du formulaire
     * @returns {object} Renvoie du statut de vérification du formulaire
     */
    function formChecks() {
        var formValidity = {
            lastNameCheck: false,
            firstNameCheck: false,
            birthDateCheck: false,
            loginCheck: false,
            mailCheck: false
        };
        formValidity.lastNameCheck = userFormChecks.lastNameCheck();
        formValidity.firstNameCheck = userFormChecks.firstNameCheck();
        formValidity.birthDateCheck = userFormChecks.birthDateCheck();
        formValidity.loginCheck = userFormChecks.loginCheck();
        formValidity.mailCheck = userFormChecks.mailCheck();
        return formValidity;
    }
}
