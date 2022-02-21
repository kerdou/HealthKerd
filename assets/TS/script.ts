window.addEventListener('load', operationsAtLoad);

/* Copie du contenu du sidebar dans le off canvas sidebar au chargement de la page */
function operationsAtLoad(): void {
    if (document.body.contains(document.getElementById('my-sidebar'))) {
        const sidebar = document.getElementById('my-sidebar') as HTMLUListElement; // trouvable dans sidebar.html
        const myOffCanvasSidebar = document.getElementById('my-offcanvas-sidebar') as HTMLUListElement; // trouvable dans offCanvas-sidebar.html
        myOffCanvasSidebar.innerHTML = sidebar.innerHTML; // copie du sidebar desktop dans le sidebar off-canvas pour la version mobile

        textAreaRidonliListenersAddition(); // Pour faire disparaitre "Informations complémentaires" au scroll des textarea
        // obligé de gérer ça dés le chargement de la page pour éviter des erreurs aprés des rechargements avec F5
        //scrollUpButton = document.getElementById("scrollUpButton");
        //scrollUpButton.addEventListener('click', scrollToTop); // When the user clicks on the button, scroll to the top of the document
        const scrollUpButton = document.getElementById('scrollUpButton') as HTMLButtonElement; // trouvable dans pageBottom.html
        scrollUpButton .addEventListener('click', scrollToTop);
    }
}

/* Retraction du menu off canvas quand le navigateur devient plus large que 992px */
let windowWidth: number = window.innerWidth;
//console.log(windowWidth);

window.addEventListener('resize', windowResize);
let offCanvasSidebar = document.getElementById('offcanvas-nav') as HTMLDivElement; // trouvable dans offCanvas-sidebar.html

/** Se déclenche au resize de la page */
function windowResize(): void {
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
function scrollFunction(): void {
    let scrollUpButton = document.getElementById('scrollUpButton') as HTMLButtonElement

    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20
    ) {
        scrollUpButton.style.visibility = 'visible';
        scrollUpButton.style.opacity = '1';
        scrollUpButton.style.cursor = 'cursor';
    } else {
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
function scrollToTop(): void {
    let scrollUpButton = document.getElementById('scrollUpButton') as HTMLButtonElement;

    if (scrollUpButton.style.opacity == '1') {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
}

// Pour faire disparaitre "Informations complémentaires" au scroll des textarea
function textAreaRidonliListenersAddition(): void {
    let ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));

    ridonList.forEach((element) => {
        element.addEventListener('scroll', textAreaScrollDown);
    });
}


function textAreaScrollDown(this: HTMLTextAreaElement): void {
    const label = this.nextElementSibling as HTMLLabelElement;
    label.style.opacity = '0';
}

if (document.body.contains(document.getElementById('docForm'))) {
    const formSubmitButton = document.getElementById('formSubmitButton') as HTMLButtonElement;
    const formResetButton = document.getElementById('formResetButton') as HTMLButtonElement;
    const telInput = document.getElementById('tel') as HTMLInputElement;
    const lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    const firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    const mailInput = document.getElementById('mail') as HTMLInputElement;
    const webpageInput = document.getElementById('webpage') as HTMLInputElement;
    const doctolibpageInput = document.getElementById('doctolibpage') as HTMLInputElement;

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
function focusOutRecheck(this: HTMLInputElement): void {
    let event = this;
    let target = event.id;
    formChecks(target);
}

/** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone quantité
 * @param {KeyboardEvent} event
 */
function telKeyCheck(event: KeyboardEvent): void {
    if (
        !(event.key >= '0' && event.key <= '9') &&
        event.key != '+' &&
        event.code != 'NumpadAdd' &&
        event.key != '.' &&
        event.code != 'NumpadDecimal' &&
        event.code != 'Backspace' &&
        event.code != 'Delete' &&
        event.code != 'ArrowLeft' &&
        event.code != 'ArrowRight' &&
        event.code != 'Tab'
    ) {
        event.preventDefault();
    } else if (event.code == 'Space') {
        event.preventDefault();
    }
}

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

function resetForm(): void {
    const lastnameInput = document.getElementById('lastname') as HTMLInputElement;
    const firstnameInput = document.getElementById('firstname') as HTMLInputElement;
    const telInput = document.getElementById('tel') as HTMLInputElement;
    const mailInput = document.getElementById('mail') as HTMLInputElement;
    const webpageInput = document.getElementById('webpage') as HTMLInputElement;
    const doctolibpageInput = document.getElementById('doctolibpage') as HTMLInputElement;
    const docForm = document.getElementById('docForm') as HTMLFormElement;

    lastnameInput.classList.remove('is-invalid');
    firstnameInput.classList.remove('is-invalid');
    telInput.classList.remove('is-invalid');
    mailInput.classList.remove('is-invalid');
    webpageInput.classList.remove('is-invalid');
    doctolibpageInput.classList.remove('is-invalid');
    docForm.reset();
}

function submitForm(): void {
    let formValidity: boolean[] = [];
    formValidity = formChecks();

    let validityStatus = formValidity.findIndex(formValidityArrayChecker);

    if (validityStatus == -1) {
        const docForm = document.getElementById('docForm') as HTMLFormElement;
        docForm.submit();
    }
}

/** Si un seul élements de formValidity est false, la valeur renvoyée sera différente de -1 */
function formValidityArrayChecker(value: boolean): boolean {
    return value == false;
}
