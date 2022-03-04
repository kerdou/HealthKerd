/** Série de vérifications des champs du formulaire
 * @param {string} target True pour afficher un message indiquant les changements à faire
 * @returns {boolean} Renvoie du statut de vérification du formulaire
 */
function formChecks(target: string = 'all'): boolean[] {
    // REGEX NOM ET PRENOM
    /** ^                                                                       Doit être placé au début de la phrase
     *   (                                                                )+    Doit contenir au moins 1 des éléments de la lite à suivre
     *    [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                 )*      Doit contenir au moins 1 des éléments de la liste et doit être suivi de 0 ou plusieurs éléments de la liste suivante
     *                                 ( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+        Doit être suivi d'un espace ou d'un ' puis suivi d'au moins 1 des éléments de la liste */
    let nameBeginning =
        "^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+";

    /**                                                                         $   Doit être placé à la fin de la phrase
     * (                                                                      )*    Peut apparaitre 0 ou plusieurs fois
     *  [-](                                                                 )+     Doit commencer par un - et être suivi d'au moins 1 élément de la liste à suivre
     *      [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                  )*       Doit contenir au moins 1 des éléments de la liste et doit être suivi de 0 ou plusieurs éléments de la liste suivante
     *                                   ( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+          Doit être suivi d'un espace ou d'un ' puis suivi d'au moins 1 des éléments de la liste */
    let nameEnding =
        "([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$";

    let nameConcat = nameBeginning + nameEnding; // Etape nécessaire avant de transformer la string en expression régulière.
    let nameModifier = 'i'; // insensible à la casse
    let nameRegex = new RegExp(nameConcat, nameModifier); // création du regex

    // REGEX TEL
    /** ^                               Doit être placé au début du numéro de tel
     *   (0|\\+33|0033)[1-9]    Doit comment par 0 ou +33 ou 0033 et être suivi d'un chiffre allant de 1 à 9. On double les \ pour que la conversion en Regex se passe bien. */
    let telBeginning = '^(0|\\+33|0033)[1-9]';

    /** ([-. ]?[0-9]{2}){4}$
     *                      $   Doit être placé à la fin de la phrase
     *                  {4}     Doit être fait 4 fois
     *  ([-. ]?[0-9]{2})        Doit commencer par - ou . ou un espace et être suivi de 2 chiffres allant chacun de 0 à 9 */
    let telEnd = '([-. ]?[0-9]{2}){4}$';

    let telConcat = telBeginning + telEnd; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
    let telRegex = new RegExp(telConcat); // création du regex

    // REGEX MAIL
    /** ^               Doit être placé au début de l'adresse mail
     *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste  */
    let mailBeginning = '^[a-z0-9._-]+';

    /** @               Doit être placé après l'arobase
     *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste */
    let mailMiddle = '@[a-z0-9._-]+';

    /** \\.         $   Doit être placé entre un . et la fin de l'adresse.  On double les \ pour que la conversion en Regex se passe bien.
     *     [a-z]{2,6}   Doit contenir entre 2 et 6 lettres présents dans la liste */
    let mailEnding = '\\.[a-z]{2,6}$';

    let mailConcat = mailBeginning + mailMiddle + mailEnding;
    let mailRegex = new RegExp(mailConcat); // création du regex

    // REGEX URL
    let urlPattern =
        '((([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9.\\-]+|(?:www.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9.\\-]+)((?:\\/[\\+~%\\/.\\w\\-_]*)?\\??(?:[\\-\\+=&;%@.\\w_]*)#? (?:[\\w]*))?)';
    let urlModifier = 'i';
    let urlRegex = new RegExp(urlPattern, urlModifier); // création du regex

    let formValidity: boolean[] = [];
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
function lastNameCheck(nameRegex: RegExp): boolean {
    const lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    let lastnameValue: string = lastnameInput.value; // variable de nom de famille
    lastnameValue = lastnameValue.trim();
    let lastnameValidity: boolean = false;

    if (lastnameValue.length < 2) {
        lastnameValidity = false;
        lastnameInput.classList.add('is-invalid');
    } else if (nameRegex.test(lastnameValue)) {
        lastnameValidity = true;
        lastnameInput.classList.remove('is-invalid');
    } else {
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
function firstNameCheck(nameRegex: RegExp): boolean {
    const firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    let firstnameValue = firstnameInput.value; // variable de prénom
    firstnameValue = firstnameValue.trim();
    let firstnameValidity = false;

    if (firstnameValue.length == 0) {
        firstnameValidity = true;
        firstnameInput.classList.remove('is-invalid');
    } else {
        if (nameRegex.test(firstnameValue)) {
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
 * @param {RegExp} telRegEx Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function telCheck(telRegex: RegExp): boolean {
    const telInput = document.getElementById('tel') as HTMLInputElement;
    let telValue: string = telInput.value; // variable du numéro de tel
    telValue = telValue.trim();
    let telValidity: boolean = false;

    if (telValue.length == 0) {
        telValidity = true;
        telInput.classList.remove('is-invalid');
    } else {
        if (telRegex.test(telValue)) {
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
 * @param {RegExp} mailRegEx Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function mailCheck(mailRegex: RegExp): boolean {
    const mailInput = document.getElementById('mail') as HTMLInputElement;
    let mailValue: string = mailInput.value; // variable d'adresse e-mail
    mailValue = mailValue.trim();
    let mailValidity: boolean = false;

    if (mailValue.length == 0) {
        mailValidity = true;
        mailInput.classList.remove('is-invalid');
    } else {
        if (mailRegex.test(mailValue)) {
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
 * @param {RegExp} urlRegex Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function webPageCheck(urlRegex: RegExp): boolean {
    const webpageInput = document.getElementById('webpage') as HTMLInputElement;
    let webpageValue: string = webpageInput.value;
    webpageValue = webpageValue.trim();
    let webPageValidity: boolean = false;

    if (webpageValue.length == 0) {
        webPageValidity = true;
        webpageInput.classList.remove('is-invalid');
    } else {
        if (urlRegex.test(webpageValue)) {
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
 * @param {RegExp} urlRegex Liste des caractères du regex
 * @returns {boolean} Renvoie l'état du test
 */
function doctolibPageCheck(urlRegex: RegExp): boolean {
    const doctolibPageInput = document.getElementById('doctolibpage') as HTMLInputElement;
    let doctolibPageValue: string = doctolibPageInput.value;
    doctolibPageValue = doctolibPageValue.trim();
    let doctolibPageValidity: boolean = false;

    if (doctolibPageValue.length == 0) {
        doctolibPageValidity = true;
        doctolibPageInput.classList.remove('is-invalid');
    } else {
        if (urlRegex.test(doctolibPageValue)) {
            doctolibPageValidity = true;
            doctolibPageInput.classList.remove('is-invalid');
        } else {
            doctolibPageValidity = false;
            doctolibPageInput.classList.add('is-invalid');
        }
    }

    return doctolibPageValidity;
}

/** Si un seul élements de formValidity est false, la valeur renvoyée sera différente de -1
*/
function formValidityArrayChecker(value: boolean): boolean {
    return value == false;
}
