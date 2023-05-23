import nameRegex from '../../../../services/regexStore/nameRegex.js';
import mailRegex from '../../../../services/regexStore/mailRegex.js';
import telRegex from '../../../../services/regexStore/telRegex.js';
import urlRegex from '../../../../services/regexStore/urlRegex.js';


/** Vérification du nom de famille:
 * * Lancement du regex 'nameRegex'
 * * Si le test n'est pas bon, le champ passe en rouge
 * @returns {boolean} - Renvoie l'état du test
 */
export function lastNameCheck(): boolean {
    const lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    let lastnameValue: string = lastnameInput.value;
    lastnameValue = lastnameValue.trim();

    let lastnameValidity = false;

    if (lastnameValue.length < 2) {
        lastnameValidity = false;
        lastnameInput.classList.add('is-invalid');
    } else {
        const lastnameTest = nameRegex(lastnameValue);

        if (lastnameTest) {
            lastnameValidity = true;
            lastnameInput.classList.remove('is-invalid');
        } else {
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
export function firstNameCheck(): boolean {
    const firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    let firstnameValue: string = firstnameInput.value;
    firstnameValue = firstnameValue.trim();

    let firstnameValidity = false;

    if (firstnameValue.length == 0) {
        firstnameValidity = true;
        firstnameInput.classList.remove('is-invalid');
    } else {
        const firstnameTest = nameRegex(firstnameValue);

        if (firstnameTest) {
            firstnameValidity = true;
            firstnameInput.classList.remove('is-invalid');
        } else {
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
export function telCheck(): boolean {
    const telInput = document.getElementById('tel') as HTMLInputElement;
    let telValue: string = telInput.value; // variable du numéro de tel
    telValue = telValue.trim();

    let telValidity = false;

    if (telValue.length == 0) {
        telValidity = true;
        telInput.classList.remove('is-invalid');
    } else {
        const telTest = telRegex(telValue);

        if (telTest) {
            telValidity = true;
            telInput.classList.remove('is-invalid');
        } else {
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
export function mailCheck(): boolean {
    const mailInput = document.getElementById('mail') as HTMLInputElement;
    let mailValue: string = mailInput.value; // variable d'adresse e-mail
    mailValue = mailValue.trim();

    let mailValidity = false;

    if (mailValue.length == 0) {
        mailValidity = true;
        mailInput.classList.remove('is-invalid');
    } else {
        const mailTest = mailRegex(mailValue);

        if (mailTest) {
            mailValidity = true;
            mailInput.classList.remove('is-invalid');
        } else {
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
export function webPageCheck(): boolean {
    const webpageInput = document.getElementById('webpage') as HTMLInputElement;
    let webpageValue: string = webpageInput.value;
    webpageValue = webpageValue.trim();

    let webPageValidity = false;

    if (webpageValue.length == 0) {
        webPageValidity = true;
        webpageInput.classList.remove('is-invalid');
    } else {
        const webpageTest = urlRegex(webpageValue);

        if (webpageTest) {
            webPageValidity = true;
            webpageInput.classList.remove('is-invalid');
        } else {
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
export function doctolibPageCheck(): boolean {
    const doctolibPageInput = document.getElementById('doctolibpage') as HTMLInputElement;
    let doctolibPageValue: string = doctolibPageInput.value;
    doctolibPageValue = doctolibPageValue.trim();

    let doctolibPageValidity = false;

    if (doctolibPageValue.length == 0) {
        doctolibPageValidity = true;
        doctolibPageInput.classList.remove('is-invalid');
    } else {
        const doctolibpageTest = urlRegex(doctolibPageValue);

        if (doctolibpageTest) {
            doctolibPageValidity = true;
            doctolibPageInput.classList.remove('is-invalid');
        } else {
            doctolibPageValidity = false;
            doctolibPageInput.classList.add('is-invalid');
        }
    }

    return doctolibPageValidity;
}