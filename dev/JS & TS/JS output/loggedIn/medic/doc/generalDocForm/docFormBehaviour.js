import * as docFormChecks from './docFormChecks.js';
import _ from 'lodash';
export default function docFormBehaviour() {
    var docForm = document.getElementById('general_doc_form_page');
    var lastnameInput = document.getElementById('lastname');
    var firstnameInput = document.getElementById('firstname');
    var telInput = document.getElementById('tel');
    var mailInput = document.getElementById('mail');
    var webpageInput = document.getElementById('webpage');
    var doctolibpageInput = document.getElementById('doctolibpage');
    var formResetButton = document.getElementById('formResetButton');
    var formSubmitButton = document.getElementById('formSubmitButton');
    fieldInputsFocusOutEventListeners();
    telInputKeyDownEventListener();
    formButtonsEventListeners();
    /** Listeners pour les champs du form quand ils perdet le focus, sert à lancer la vérification des champs
     */
    function fieldInputsFocusOutEventListeners() {
        lastnameInput.addEventListener('input', _.debounce(focusOutRecheck, 150));
        firstnameInput.addEventListener('input', _.debounce(focusOutRecheck, 150));
        telInput.addEventListener('input', _.debounce(focusOutRecheck, 150));
        mailInput.addEventListener('input', _.debounce(focusOutRecheck, 150));
        webpageInput.addEventListener('input', _.debounce(focusOutRecheck, 150));
        doctolibpageInput.addEventListener('input', _.debounce(focusOutRecheck, 150));
    }
    /** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide
    */
    function focusOutRecheck() {
        formChecks();
    }
    /** Listener des touches du clavier pour le champ de téléphone
     */
    function telInputKeyDownEventListener() {
        telInput.addEventListener('keydown', telKeyCheck);
    }
    /** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone
     * @param {KeyboardEvent} event
     */
    function telKeyCheck(event) {
        if (!(event.key >= '0' && event.key <= '9') &&
            event.key != '+' &&
            event.code != 'NumpadAdd' &&
            event.key != '.' &&
            event.code != 'NumpadDecimal' &&
            event.code != 'Backspace' &&
            event.code != 'Delete' &&
            event.code != 'ArrowLeft' &&
            event.code != 'ArrowRight' &&
            event.code != 'Tab') {
            event.preventDefault();
        }
        else if (event.code == 'Space') {
            event.preventDefault();
        }
    }
    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    function formButtonsEventListeners() {
        if (formResetButton != null) {
            formResetButton.addEventListener('click', resetForm);
        }
        formSubmitButton.addEventListener('click', submitForm);
    }
    /** Reset du form et des classes des champs inputs
     */
    function resetForm() {
        lastnameInput.classList.remove('is-invalid');
        firstnameInput.classList.remove('is-invalid');
        telInput.classList.remove('is-invalid');
        mailInput.classList.remove('is-invalid');
        webpageInput.classList.remove('is-invalid');
        doctolibpageInput.classList.remove('is-invalid');
        docForm.reset();
    }
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    function submitForm() {
        var formValidityResult = {};
        formValidityResult = formChecks();
        var formHasIssues = _.includes(formValidityResult, false);
        // Si aucun test ne renvoie false, on peut submit le form
        if (!formHasIssues) {
            docForm.submit();
        }
    }
    /** Série de vérifications des champs du formulaire
     * @returns {object} Renvoie du statut de vérification du formulaire
     */
    function formChecks() {
        var formValidity = {
            lastNameCheck: false,
            firstNameCheck: false,
            telCheck: false,
            mailCheck: false,
            webPageCheck: false,
            doctolibPageCheck: false
        };
        formValidity.lastNameCheck = docFormChecks.lastNameCheck();
        formValidity.firstNameCheck = docFormChecks.firstNameCheck();
        formValidity.telCheck = docFormChecks.telCheck();
        formValidity.mailCheck = docFormChecks.mailCheck();
        formValidity.webPageCheck = docFormChecks.webPageCheck();
        formValidity.doctolibPageCheck = docFormChecks.doctolibPageCheck();
        return formValidity;
    }
}
