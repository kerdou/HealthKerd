import NameRegex from '../../../services/regexStore/nameRegex.js';
import MailRegex from '../../../services/regexStore/mailRegex.js';
import TelRegex from '../../../services/regexStore/telRegex.js';
import UrlRegex from '../../../services/regexStore/urlRegex.js';
var DocFormChecks = /** @class */ (function () {
    function DocFormChecks() {
    }
    /** Vérification du nom de famille:
     * * Lancement du regex 'nameRegex'
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    DocFormChecks.prototype.lastNameCheck = function () {
        var lastnameInput = document.getElementById('lastname');
        var lastnameValue = lastnameInput.value;
        lastnameValue = lastnameValue.trim();
        var lastnameValidity = false;
        if (lastnameValue.length < 2) {
            lastnameValidity = false;
            lastnameInput.classList.add('is-invalid');
        }
        else {
            var lastnameTestObj = new NameRegex;
            var lastnameTest = lastnameTestObj.nameRegex(lastnameValue);
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
    };
    /** Vérification du prénom:
     * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    DocFormChecks.prototype.firstNameCheck = function () {
        var firstnameInput = document.getElementById('firstname');
        var firstnameValue = firstnameInput.value;
        firstnameValue = firstnameValue.trim();
        var firstnameValidity = false;
        if (firstnameValue.length == 0) {
            firstnameValidity = true;
            firstnameInput.classList.remove('is-invalid');
        }
        else {
            var firstnameTestObj = new NameRegex;
            var firstnameTest = firstnameTestObj.nameRegex(firstnameValue);
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
    };
    /** Vérification du numéro de téléphone:
     * * Si le numéro n'est pas vide, lancer a vérification du numéro
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    DocFormChecks.prototype.telCheck = function () {
        var telInput = document.getElementById('tel');
        var telValue = telInput.value; // variable du numéro de tel
        telValue = telValue.trim();
        var telValidity = false;
        if (telValue.length == 0) {
            telValidity = true;
            telInput.classList.remove('is-invalid');
        }
        else {
            var telTestObj = new TelRegex;
            var telTest = telTestObj.telRegex(telValue);
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
    };
    /** Vérification du mail:
     * * Vérification de la syntaxe de l'adresse
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    DocFormChecks.prototype.mailCheck = function () {
        var mailInput = document.getElementById('mail');
        var mailValue = mailInput.value; // variable d'adresse e-mail
        mailValue = mailValue.trim();
        var mailValidity = false;
        if (mailValue.length == 0) {
            mailValidity = true;
            mailInput.classList.remove('is-invalid');
        }
        else {
            var mailTestObj = new MailRegex;
            var mailTest = mailTestObj.mailRegex(mailValue);
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
    };
    /** Vérification de l'url de page perso:
     * * Vérification de la syntaxe de l'adresse
     * * Si le test n'est pas bon, ajout du message d'erreur et le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    DocFormChecks.prototype.webPageCheck = function () {
        var webpageInput = document.getElementById('webpage');
        var webpageValue = webpageInput.value;
        webpageValue = webpageValue.trim();
        var webPageValidity = false;
        if (webpageValue.length == 0) {
            webPageValidity = true;
            webpageInput.classList.remove('is-invalid');
        }
        else {
            var webpageTestObj = new UrlRegex;
            var webpageTest = webpageTestObj.urlRegex(webpageValue);
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
    };
    /** Vérification de l'url de page doctolib:
     * * Vérification de la syntaxe de l'adresse
     * * Si le test n'est pas bon, ajout du message d'erreur et le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    DocFormChecks.prototype.doctolibPageCheck = function () {
        var doctolibPageInput = document.getElementById('doctolibpage');
        var doctolibPageValue = doctolibPageInput.value;
        doctolibPageValue = doctolibPageValue.trim();
        var doctolibPageValidity = false;
        if (doctolibPageValue.length == 0) {
            doctolibPageValidity = true;
            doctolibPageInput.classList.remove('is-invalid');
        }
        else {
            var doctolibpageTestObj = new UrlRegex;
            var doctolibpageTest = doctolibpageTestObj.urlRegex(doctolibPageValue);
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
    };
    return DocFormChecks;
}());
export default DocFormChecks;
