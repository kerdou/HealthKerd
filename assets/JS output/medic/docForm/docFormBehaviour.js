"use strict";
if (document.body.contains(document.getElementById('doc_form_page'))) {
    var formSubmitButton = document.getElementById('formSubmitButton');
    var formResetButton = document.getElementById('formResetButton');
    var telInput = document.getElementById('tel');
    var lastnameInput = document.getElementById('lastname');
    var firstnameInput = document.getElementById('firstname');
    var mailInput = document.getElementById('mail');
    var webpageInput = document.getElementById('webpage');
    var doctolibpageInput = document.getElementById('doctolibpage');
    // boutons de validation et de reset du form
    formSubmitButton.addEventListener('click', submitForm);
    if (document.body.contains(document.getElementById('formResetButton'))) {
        formResetButton.addEventListener('click', resetForm);
    }
    // bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
    telInput.addEventListener('keydown', telKeyCheck);
    // relance la vérification des champs à chaque fois que l'un d'eux perd le focus
    lastnameInput.addEventListener('focusout', focusOutRecheck);
    firstnameInput.addEventListener('focusout', focusOutRecheck);
    telInput.addEventListener('focusout', focusOutRecheck);
    mailInput.addEventListener('focusout', focusOutRecheck);
    webpageInput.addEventListener('focusout', focusOutRecheck);
    doctolibpageInput.addEventListener('focusout', focusOutRecheck);
}
/** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide
*/
function focusOutRecheck() {
    var event = this;
    var target = event.id;
    formChecks(target);
}
/** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone quantité
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
/** Reset du form et des classes des champs inputs
 */
function resetForm() {
    var lastnameInput = document.getElementById('lastname');
    var firstnameInput = document.getElementById('firstname');
    var telInput = document.getElementById('tel');
    var mailInput = document.getElementById('mail');
    var webpageInput = document.getElementById('webpage');
    var doctolibpageInput = document.getElementById('doctolibpage');
    var docForm = document.getElementById('docForm');
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
    var formValidity = [];
    formValidity = formChecks();
    var validityStatus = formValidity.findIndex(formValidityArrayChecker);
    if (validityStatus == -1) {
        var docForm = document.getElementById('doc_form_page');
        docForm.submit();
    }
}
