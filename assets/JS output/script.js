"use strict";
window.addEventListener('load', operationsAtLoad);
/* Copie du contenu du sidebar dans le off canvas sidebar au chargement de la page */
function operationsAtLoad() {
    if (document.body.contains(document.getElementById('my-sidebar'))) {
        var sidebar = document.getElementById('my-sidebar'); // trouvable dans sidebar.html
        var myOffCanvasSidebar = document.getElementById('my-offcanvas-sidebar'); // trouvable dans offCanvas-sidebar.html
        myOffCanvasSidebar.innerHTML = sidebar.innerHTML; // copie du sidebar desktop dans le sidebar off-canvas pour la version mobile
        textAreaRidonliListenersAddition(); // Pour faire disparaitre "Informations complémentaires" au scroll des textarea
        // obligé de gérer ça dés le chargement de la page pour éviter des erreurs aprés des rechargements avec F5
        //scrollUpButton = document.getElementById("scrollUpButton");
        //scrollUpButton.addEventListener('click', scrollToTop); // When the user clicks on the button, scroll to the top of the document
        var scrollUpButton = document.getElementById('scrollUpButton'); // trouvable dans pageBottom.html
        scrollUpButton.addEventListener('click', scrollToTop);
    }
}
/* Retraction du menu off canvas quand le navigateur devient plus large que 992px */
var windowWidth = window.innerWidth;
//console.log(windowWidth);
window.addEventListener('resize', windowResize);
var offCanvasSidebar = document.getElementById('offcanvas-nav'); // trouvable dans offCanvas-sidebar.html
/** Se déclenche au resize de la page */
function windowResize() {
    windowWidth = window.innerWidth;
    /** Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
     * Bootstrap fait apparaitre un <div class="offcanvas-backdrop fade show" qui grise le reste de l'écran
     * Quand on supprime la classe "show" du sidebar, il faut aussi supprimer cette div supplémentaire
     * pour supprimer ce grisage
     */
    if (windowWidth >= 992 && offCanvasSidebar.classList.contains('show')) {
        offCanvasSidebar.classList.remove('show');
        if (document.getElementsByClassName('offcanvas-backdrop').length != 0) {
            document.getElementsByClassName('offcanvas-backdrop')[0].remove();
        }
    }
}
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
};
/**   */
function scrollFunction() {
    var scrollUpButton = document.getElementById('scrollUpButton');
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollUpButton.style.visibility = 'visible';
        scrollUpButton.style.opacity = '1';
        scrollUpButton.style.cursor = 'cursor';
    }
    else {
        scrollUpButton.style.opacity = '0';
        // retard de visiblity=hiden et pointer=none pour garantir une disparition fluide du scrollUpButton
        setTimeout(function () {
            // le if évite d'avoir des changements intempestifs d'état
            if (scrollUpButton.style.opacity == '0') {
                scrollUpButton.style.visibility = 'hidden';
                scrollUpButton.style.cursor = 'none';
            }
        }, 300);
    }
}
// remonte l'écran quand la fonction est activée
function scrollToTop() {
    var scrollUpButton = document.getElementById('scrollUpButton');
    if (scrollUpButton.style.opacity == '1') {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
}
// Pour faire disparaitre "Informations complémentaires" au scroll des textarea
function textAreaRidonliListenersAddition() {
    var ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));
    ridonList.forEach(function (element) {
        element.addEventListener('scroll', textAreaScrollDown);
    });
}
function textAreaScrollDown() {
    var label = this.nextElementSibling;
    label.style.opacity = '0';
}
if (document.body.contains(document.getElementById('docForm'))) {
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
/** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide */
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
/** Série de vérifications des champs du formulaire
 * @param {string} target True pour afficher un message indiquant les changements à faire
 * @returns {boolean} Renvoie du statut de vérification du formulaire
 */
function formChecks(target) {
    if (target === void 0) { target = 'all'; }
    // REGEX NOM ET PRENOM
    /** ^                                                                       Doit être placé au début de la phrase
     *   (                                                                )+    Doit contenir au moins 1 des éléments de la lite à suivre
     *    [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                 )*      Doit contenir au moins 1 des éléments de la liste et doit être suivi de 0 ou plusieurs éléments de la liste suivante
     *                                 ( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+        Doit être suivi d'un espace ou d'un ' puis suivi d'au moins 1 des éléments de la liste */
    var nameBeginning = "^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+";
    /**                                                                         $   Doit être placé à la fin de la phrase
     * (                                                                      )*    Peut apparaitre 0 ou plusieurs fois
     *  [-](                                                                 )+     Doit commencer par un - et être suivi d'au moins 1 élément de la liste à suivre
     *      [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                  )*       Doit contenir au moins 1 des éléments de la liste et doit être suivi de 0 ou plusieurs éléments de la liste suivante
     *                                   ( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+          Doit être suivi d'un espace ou d'un ' puis suivi d'au moins 1 des éléments de la liste */
    var nameEnding = "([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$";
    var nameConcat = nameBeginning + nameEnding; // Etape nécessaire avant de transformer la string en expression régulière.
    var nameModifier = 'i'; // insensible à la casse
    var nameRegex = new RegExp(nameConcat, nameModifier); // création du regex
    // REGEX TEL
    /** ^                               Doit être placé au début du numéro de tel
     *   (0|\\+33|0033)[1-9]    Doit comment par 0 ou +33 ou 0033 et être suivi d'un chiffre allant de 1 à 9. On double les \ pour que la conversion en Regex se passe bien. */
    var telBeginning = '^(0|\\+33|0033)[1-9]';
    /** ([-. ]?[0-9]{2}){4}$
     *                      $   Doit être placé à la fin de la phrase
     *                  {4}     Doit être fait 4 fois
     *  ([-. ]?[0-9]{2})        Doit commencer par - ou . ou un espace et être suivi de 2 chiffres allant chacun de 0 à 9 */
    var telEnd = '([-. ]?[0-9]{2}){4}$';
    var telConcat = telBeginning + telEnd; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
    var telRegex = new RegExp(telConcat); // création du regex
    // REGEX MAIL
    /** ^               Doit être placé au début de l'adresse mail
     *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste  */
    var mailBeginning = '^[a-z0-9._-]+';
    /** @               Doit être placé après l'arobase
     *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste */
    var mailMiddle = '@[a-z0-9._-]+';
    /** \\.         $   Doit être placé entre un . et la fin de l'adresse.  On double les \ pour que la conversion en Regex se passe bien.
     *     [a-z]{2,6}   Doit contenir entre 2 et 6 lettres présents dans la liste */
    var mailEnding = '\\.[a-z]{2,6}$';
    var mailConcat = mailBeginning + mailMiddle + mailEnding;
    var mailRegex = new RegExp(mailConcat); // création du regex
    // REGEX URL
    var urlPattern = '((([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9.\\-]+|(?:www.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9.\\-]+)((?:\\/[\\+~%\\/.\\w\\-_]*)?\\??(?:[\\-\\+=&;%@.\\w_]*)#? (?:[\\w]*))?)';
    var urlModifier = 'i';
    var urlRegex = new RegExp(urlPattern, urlModifier); // création du regex
    var formValidity = [];
    formValidity[0] = lastNameCheck(nameRegex);
    formValidity[1] = firstNameCheck(nameRegex);
    formValidity[2] = telCheck(telRegex);
    formValidity[3] = mailCheck(mailRegex);
    formValidity[4] = webPageCheck(urlRegex);
    formValidity[5] = doctolibPageCheck(urlRegex);
    return formValidity;
}
/** Vérification du nom de famille:
 * * Lancement du regex 'nameRegex'
 * * Si le test n'est pas bon, le champ passe en rouge
 * @param {RegExp} nameRegex Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function lastNameCheck(nameRegex) {
    var lastnameInput = document.getElementById('lastname');
    var lastnameValue = lastnameInput.value; // variable de nom de famille
    lastnameValue = lastnameValue.trim();
    var lastnameValidity = false;
    if (lastnameValue.length < 2) {
        lastnameValidity = false;
        lastnameInput.classList.add('is-invalid');
    }
    else if (nameRegex.test(lastnameValue)) {
        lastnameValidity = true;
        lastnameInput.classList.remove('is-invalid');
    }
    else {
        lastnameValidity = false;
        lastnameInput.classList.add('is-invalid');
    }
    return lastnameValidity;
}
/** Vérification du prénom:
 * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
 * * Si le test n'est pas bon, le champ passe en rouge
 * @param {RegExp} nameRegex Liste des caractères du regex
 * @returns {boolean} Renvoie un message d'erreur si nécessaire
 */
function firstNameCheck(nameRegex) {
    var firstnameInput = document.getElementById('firstname');
    var firstnameValue = firstnameInput.value; // variable de prénom
    firstnameValue = firstnameValue.trim();
    var firstnameValidity = false;
    if (firstnameValue.length == 0) {
        firstnameValidity = true;
        firstnameInput.classList.remove('is-invalid');
    }
    else {
        if (nameRegex.test(firstnameValue)) {
            firstnameValidity = true;
            firstnameInput.classList.remove('is-invalid');
        }
        else {
            firstnameValidity = false;
            firstnameInput.classList.add('is-invalid');
        }
    }
    return firstnameValidity;
}
/** Vérification du numéro de téléphone:
 * * Si le numéro n'est pas vide, lancer a vérification du numéro
 * * Si le test n'est pas bon, le champ passe en rouge
 * @param {RegExp} telRegEx Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function telCheck(telRegex) {
    var telInput = document.getElementById('tel');
    var telValue = telInput.value; // variable du numéro de tel
    telValue = telValue.trim();
    var telValidity = false;
    if (telValue.length == 0) {
        telValidity = true;
        telInput.classList.remove('is-invalid');
    }
    else {
        if (telRegex.test(telValue)) {
            telValidity = true;
            telInput.classList.remove('is-invalid');
        }
        else {
            telValidity = false;
            telInput.classList.add('is-invalid');
        }
    }
    return telValidity;
}
/** Vérification du mail:
 * * Vérification de la syntaxe de l'adresse
 * * Si le test n'est pas bon, le champ passe en rouge
 * @param {RegExp} mailRegEx Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function mailCheck(mailRegex) {
    var mailInput = document.getElementById('mail');
    var mailValue = mailInput.value; // variable d'adresse e-mail
    mailValue = mailValue.trim();
    var mailValidity = false;
    if (mailValue.length == 0) {
        mailValidity = true;
        mailInput.classList.remove('is-invalid');
    }
    else {
        if (mailRegex.test(mailValue)) {
            mailValidity = true;
            mailInput.classList.remove('is-invalid');
        }
        else {
            mailValidity = false;
            mailInput.classList.add('is-invalid');
        }
    }
    return mailValidity;
}
/** Vérification de l'url de page perso:
 * * Vérification de la syntaxe de l'adresse
 * * Si le test n'est pas bon, ajout du message d'erreur et le champ passe en rouge
 * @param {RegExp} urlRegex Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function webPageCheck(urlRegex) {
    var webpageInput = document.getElementById('webpage');
    var webpageValue = webpageInput.value;
    webpageValue = webpageValue.trim();
    var webPageValidity = false;
    if (webpageValue.length == 0) {
        webPageValidity = true;
        webpageInput.classList.remove('is-invalid');
    }
    else {
        if (urlRegex.test(webpageValue)) {
            webPageValidity = true;
            webpageInput.classList.remove('is-invalid');
        }
        else {
            webPageValidity = false;
            webpageInput.classList.add('is-invalid');
        }
    }
    return webPageValidity;
}
/** Vérification de l'url de page doctolib:
 * * Vérification de la syntaxe de l'adresse
 * * Si le test n'est pas bon, ajout du message d'erreur et le champ passe en rouge
 * @param {RegExp} urlRegex Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function doctolibPageCheck(urlRegex) {
    var doctolibPageInput = document.getElementById('doctolibpage');
    var doctolibPageValue = doctolibPageInput.value;
    doctolibPageValue = doctolibPageValue.trim();
    var doctolibPageValidity = false;
    if (doctolibPageValue.length == 0) {
        doctolibPageValidity = true;
        doctolibPageInput.classList.remove('is-invalid');
    }
    else {
        if (urlRegex.test(doctolibPageValue)) {
            doctolibPageValidity = true;
            doctolibPageInput.classList.remove('is-invalid');
        }
        else {
            doctolibPageValidity = false;
            doctolibPageInput.classList.add('is-invalid');
        }
    }
    return doctolibPageValidity;
}
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
function submitForm() {
    var formValidity = [];
    formValidity = formChecks();
    var validityStatus = formValidity.findIndex(formValidityArrayChecker);
    if (validityStatus == -1) {
        var docForm = document.getElementById('docForm');
        docForm.submit();
    }
}
/** Si un seul élements de formValidity est false, la valeur renvoyée sera différente de -1 */
function formValidityArrayChecker(value) {
    return value == false;
}
