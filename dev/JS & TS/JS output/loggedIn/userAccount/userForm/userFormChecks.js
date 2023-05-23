import nameRegex from '../../../services/regexStore/nameRegex.js';
import mailRegex from '../../../services/regexStore/mailRegex.js';
import frenchDateRegex from '../../../services/regexStore/dateRegex.js';
import isExists from 'date-fns/isExists';
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
/** Vérification du login:
 * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
 * * Si le test n'est pas bon, le champ passe en rouge
 * @returns {boolean} - Renvoie l'état du test
 */
export function birthDateCheck() {
    var birthDateInput = document.getElementById('birthDate');
    var birthDateValue = birthDateInput.value;
    birthDateValue = birthDateValue.trim();
    var birthDateValidity = false;
    if (birthDateValue.length == 0) {
        birthDateValidity = true;
        birthDateInput.classList.remove('is-invalid');
    }
    else {
        var birthDateTest = frenchDateRegex(birthDateValue);
        if (birthDateTest) {
            var splittedDate = birthDateValue.split('/');
            var nbrSplittedDate_1 = {
                year: 0,
                month: 0,
                day: 0
            };
            splittedDate.map(function (value, index) {
                switch (index) {
                    case 0:
                        nbrSplittedDate_1.day = +value;
                        break;
                    case 1:
                        nbrSplittedDate_1.month = +value - 1;
                        break;
                    case 2:
                        nbrSplittedDate_1.year = +value;
                        break;
                }
            });
            var dateValidity = isExists(nbrSplittedDate_1.year, nbrSplittedDate_1.month, nbrSplittedDate_1.day);
            if (dateValidity) {
                birthDateValidity = true;
                birthDateInput.classList.remove('is-invalid');
            }
            else {
                birthDateValidity = false;
                birthDateInput.classList.add('is-invalid');
            }
        }
        else {
            birthDateValidity = false;
            birthDateInput.classList.add('is-invalid');
        }
    }
    return birthDateValidity;
}
/** Vérification du login:
 * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
 * * Si le test n'est pas bon, le champ passe en rouge
 * @returns {boolean} - Renvoie l'état du test
 */
export function loginCheck() {
    var loginInput = document.getElementById('login');
    var loginValue = loginInput.value;
    loginValue = loginValue.trim();
    var loginValidity = false;
    if (loginValue.length == 0) {
        loginValidity = true;
        loginInput.classList.remove('is-invalid');
    }
    else {
        var loginTest = nameRegex(loginValue);
        if (loginTest) {
            loginValidity = true;
            loginInput.classList.remove('is-invalid');
        }
        else {
            loginValidity = false;
            loginInput.classList.add('is-invalid');
        }
    }
    return loginValidity;
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
