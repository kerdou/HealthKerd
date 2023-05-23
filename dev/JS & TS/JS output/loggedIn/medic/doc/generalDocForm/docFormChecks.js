import nameRegex from '../../../../services/regexStore/nameRegex.js';
import mailRegex from '../../../../services/regexStore/mailRegex.js';
import telRegex from '../../../../services/regexStore/telRegex.js';
import urlRegex from '../../../../services/regexStore/urlRegex.js';
/** Vérification du nom de famille:
 * * Lancement du regex 'nameRegex'
 * * Si le test n'est pas bon, le champ passe en rouge
 * @returns {boolean} - Renvoie l'état du test
 */
export function lastNameCheck() {
    var lastnameInput = document.getElementById('lastname');
    var lastnameValue = lastnameInput.value;
    lastnameValue = lastnameValue.trim();
    var lastnameValidity = false;
    if (lastnameValue.length < 2) {
        lastnameValidity = false;
        lastnameInput.classList.add('is-invalid');
    }
    else {
        var lastnameTest = nameRegex(lastnameValue);
        if (lastnameTest) {
            lastnameValidity = true;
            lastnameInput.classList.remove('is-invalid');
        }
        else {
            lastnameValidity = false;
            lastnameInput.classList.add('is-invalid');
        }
    }
    return lastnameValidity;
}
/** Vérification du prénom:
 * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
 * * Si le test n'est pas bon, le champ passe en rouge
 * @returns {boolean} - Renvoie l'état du test
 */
export function firstNameCheck() {
    var firstnameInput = document.getElementById('firstname');
    var firstnameValue = firstnameInput.value;
    firstnameValue = firstnameValue.trim();
    var firstnameValidity = false;
    if (firstnameValue.length == 0) {
        firstnameValidity = true;
        firstnameInput.classList.remove('is-invalid');
    }
    else {
        var firstnameTest = nameRegex(firstnameValue);
        if (firstnameTest) {
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
 * @returns {boolean} - Renvoie l'état du test
 */
export function telCheck() {
    var telInput = document.getElementById('tel');
    var telValue = telInput.value; // variable du numéro de tel
    telValue = telValue.trim();
    var telValidity = false;
    if (telValue.length == 0) {
        telValidity = true;
        telInput.classList.remove('is-invalid');
    }
    else {
        var telTest = telRegex(telValue);
        if (telTest) {
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
 * @returns {boolean} - Renvoie l'état du test
 */
export function mailCheck() {
    var mailInput = document.getElementById('mail');
    var mailValue = mailInput.value; // variable d'adresse e-mail
    mailValue = mailValue.trim();
    var mailValidity = false;
    if (mailValue.length == 0) {
        mailValidity = true;
        mailInput.classList.remove('is-invalid');
    }
    else {
        var mailTest = mailRegex(mailValue);
        if (mailTest) {
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
 * @returns {boolean} - Renvoie l'état du test
 */
export function webPageCheck() {
    var webpageInput = document.getElementById('webpage');
    var webpageValue = webpageInput.value;
    webpageValue = webpageValue.trim();
    var webPageValidity = false;
    if (webpageValue.length == 0) {
        webPageValidity = true;
        webpageInput.classList.remove('is-invalid');
    }
    else {
        var webpageTest = urlRegex(webpageValue);
        if (webpageTest) {
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
 * @returns {boolean} - Renvoie l'état du test
 */
export function doctolibPageCheck() {
    var doctolibPageInput = document.getElementById('doctolibpage');
    var doctolibPageValue = doctolibPageInput.value;
    doctolibPageValue = doctolibPageValue.trim();
    var doctolibPageValidity = false;
    if (doctolibPageValue.length == 0) {
        doctolibPageValidity = true;
        doctolibPageInput.classList.remove('is-invalid');
    }
    else {
        var doctolibpageTest = urlRegex(doctolibPageValue);
        if (doctolibpageTest) {
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
