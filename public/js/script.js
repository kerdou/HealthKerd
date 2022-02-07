window.addEventListener("load", operationsAtLoad);

/* Copie du contenu du sidebar dans le off canvas sidebar au chargement de la page */
function operationsAtLoad() {
  if (document.body.contains(document.getElementById("my-sidebar"))) {
    let mySideBarContent = document.getElementById("my-sidebar").innerHTML;
    let myOffCanvasSidebar = document.getElementById("my-offcanvas-sidebar");
    myOffCanvasSidebar.innerHTML = mySideBarContent; // copie du sidebar desktop dans le sidebar off-canvas pour la version mobile

    textAreaRidonliListenersAddition(); // Pour faire disparaitre "Informations complémentaires" au scroll des textarea

    // obligé de gérer ça dés le chargement de la page pour éviter des erreurs aprés des rechargements avec F5
    //scrollUpButton = document.getElementById("scrollUpButton");
    //scrollUpButton.addEventListener('click', scrollToTop); // When the user clicks on the button, scroll to the top of the document
    document
      .getElementById("scrollUpButton")
      .addEventListener("click", scrollToTop);
  }
}

/* Retraction du menu off canvas quand le navigateur devient plus large que 992px */
let windowWidth = window.innerWidth;
//console.log(windowWidth);

window.addEventListener("resize", windowResize);
let offCanvasSidebar = document.getElementById("offcanvas-nav");

/** Se déclenche au resize de la page */
function windowResize() {
  windowWidth = window.innerWidth;

  /** Au delà de 992px de large (format LG sur Bootstrap), l'offcanvas menu se rétracte
   * Bootstrap fait apparaitre un <div class="offcanvas-backdrop fade show" qui grise le reste de l'écran
   * Quand on supprime la classe "show" du sidebar, il faut aussi supprimer cette div supplémentaire
   * pour supprimer ce grisage
   */
  if (windowWidth >= 992 && offCanvasSidebar.classList.contains("show")) {
    offCanvasSidebar.classList.remove("show");

    if (document.getElementsByClassName("offcanvas-backdrop").length != 0) {
      document.getElementsByClassName("offcanvas-backdrop")[0].remove();
    }
  }
}

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
  scrollFunction();
};

/**   */
function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("scrollUpButton").style.visibility = "visible";
    document.getElementById("scrollUpButton").style.opacity = 1;
    document.getElementById("scrollUpButton").style.pointer = "cursor";
  } else {
    document.getElementById("scrollUpButton").style.opacity = 0;

    // retard de visiblity=hiden et pointer=none pour garantir une disparition fluide du scrollUpButton
    setTimeout(function () {
      // le if évite d'avoir des changements intempestifs d'état
      if (document.getElementById("scrollUpButton").style.opacity == 0) {
        document.getElementById("scrollUpButton").style.visibility = "hidden";
        document.getElementById("scrollUpButton").style.pointer = "none";
      }
    }, 300);
  }
}

// remonte l'écran quand la fonction est activée
function scrollToTop() {
  if (scrollUpButton.style.opacity == 1) {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  }
}

// Pour faire disparaitre "Informations complémentaires" au scroll des textarea
function textAreaRidonliListenersAddition() {
  let ridonList = Array.from(
    document.getElementsByClassName("textarea-ridonli")
  );

  ridonList.forEach((element) => {
    element.addEventListener("scroll", textAreaScrollDown);
  });
}

function textAreaScrollDown() {
  this.nextElementSibling.style.opacity = 0;
}

if (document.body.contains(document.getElementById("docForm"))) {
  document
    .getElementById("formSubmitButton")
    .addEventListener("click", submitForm);

  if (document.body.contains(document.getElementById("formResetButton"))) {
    document
      .getElementById("formResetButton")
      .addEventListener("click", resetForm);
  }

  // bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
  document.getElementById("tel").addEventListener("keydown", telKeyCheck);

  // relance la vérification des champs à chaque fois que l'un d'eux perd le focus
  document
    .getElementById("lastname")
    .addEventListener("focusout", focusOutRecheck);
  document
    .getElementById("firstname")
    .addEventListener("focusout", focusOutRecheck);
  document.getElementById("tel").addEventListener("focusout", focusOutRecheck);
  document.getElementById("mail").addEventListener("focusout", focusOutRecheck);
  document
    .getElementById("webpage")
    .addEventListener("focusout", focusOutRecheck);
  document
    .getElementById("doctolibpage")
    .addEventListener("focusout", focusOutRecheck);
}

/** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide */
function focusOutRecheck() {
  let event = this;
  let target = event.id;
  formChecks(target);
}

/** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone quantité
 * @param {event} event
 */
function telKeyCheck(event) {
  if (
    !(event.key >= 0 && event.key <= 9) &&
    event.key != "+" &&
    event.code != "NumpadAdd" &&
    event.key != "." &&
    event.code != "NumpadDecimal" &&
    event.code != "Backspace" &&
    event.code != "Delete" &&
    event.code != "ArrowLeft" &&
    event.code != "ArrowRight" &&
    event.code != "Tab"
  ) {
    event.preventDefault();
  } else if (event.code == "Space") {
    event.preventDefault();
  }
}

/** Série de vérifications des champs du formulaire
 * @param {string} target True pour afficher un message indiquant les changements à faire
 * @returns {boolean} Renvoie du statut de vérification du formulaire
 */
function formChecks(target = "all") {
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
  let nameModifier = "i"; // insensible à la casse
  let nameRegex = new RegExp(nameConcat, nameModifier); // création du regex

  // REGEX TEL
  /** ^                               Doit être placé au début du numéro de tel
   *   (0|\\+33|0033)[1-9]    Doit comment par 0 ou +33 ou 0033 et être suivi d'un chiffre allant de 1 à 9. On double les \ pour que la conversion en Regex se passe bien. */
  let telBeginning = "^(0|\\+33|0033)[1-9]";

  /** ([-. ]?[0-9]{2}){4}$
   *                      $   Doit être placé à la fin de la phrase
   *                  {4}     Doit être fait 4 fois
   *  ([-. ]?[0-9]{2})        Doit commencer par - ou . ou un espace et être suivi de 2 chiffres allant chacun de 0 à 9 */
  let telEnd = "([-. ]?[0-9]{2}){4}$";

  let telConcat = telBeginning + telEnd; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
  let telRegex = new RegExp(telConcat); // création du regex

  // REGEX MAIL
  /** ^               Doit être placé au début de l'adresse mail
   *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste  */
  let mailBeginning = "^[a-z0-9._-]+";

  /** @               Doit être placé après l'arobase
   *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste */
  let mailMiddle = "@[a-z0-9._-]+";

  /** \\.         $   Doit être placé entre un . et la fin de l'adresse.  On double les \ pour que la conversion en Regex se passe bien.
   *     [a-z]{2,6}   Doit contenir entre 2 et 6 lettres présents dans la liste */
  let mailEnding = "\\.[a-z]{2,6}$";

  let mailConcat = mailBeginning + mailMiddle + mailEnding;
  let mailRegex = new RegExp(mailConcat); // création du regex

  // REGEX URL
  let urlPattern =
    "((([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9.\\-]+|(?:www.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9.\\-]+)((?:\\/[\\+~%\\/.\\w\\-_]*)?\\??(?:[\\-\\+=&;%@.\\w_]*)#? (?:[\\w]*))?)";
  let urlModifier = "i";
  let urlRegex = new RegExp(urlPattern, urlModifier); // création du regex

  let formValidity = [];
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
 * @param {string} nameRegex Liste des caractères du regex
 * @returns {bool} Renvoie l'état du test
 */
function lastNameCheck(nameRegex) {
  let lastnameValue = document.getElementById("lastname").value; // variable de nom de famille
  lastnameValue = lastnameValue.trim();
  let lastnameValidity = false;

  if (lastnameValue.length < 2) {
    lastnameValidity = false;
    document.getElementById("lastname").classList.add("is-invalid");
  } else if (nameRegex.test(lastnameValue)) {
    lastnameValidity = true;
    document.getElementById("lastname").classList.remove("is-invalid");
  } else {
    lastnameValidity = false;
    document.getElementById("lastname").classList.add("is-invalid");
  }

  return lastnameValidity;
}

/** Vérification du prénom:
 * * Si le champ n'est pas vide, lancer la fonction de vérification orthographique
 * * Si le test n'est pas bon, le champ passe en rouge
 * @param {string} nameRegex Liste des caractères du regex
 * @returns {bool} Renvoie un message d'erreur si nécessaire
 */
function firstNameCheck(nameRegex) {
  let firstnameValue = document.getElementById("firstname").value; // variable de prénom
  firstnameValue = firstnameValue.trim();
  let firstnameValidity = false;

  if (firstnameValue.length == 0) {
    firstnameValidity = true;
    document.getElementById("firstname").classList.remove("is-invalid");
  } else {
    if (nameRegex.test(firstnameValue)) {
      firstnameValidity = true;
      document.getElementById("firstname").classList.remove("is-invalid");
    } else {
      firstnameValidity = false;
      document.getElementById("firstname").classList.add("is-invalid");
    }
  }

  return firstnameValidity;
}

/** Vérification du numéro de téléphone:
 * * Si le numéro n'est pas vide, lancer a vérification du numéro
 * * Si le test n'est pas bon, le champ passe en rouge
 * @param {string} telRegEx Liste des caractères du regex
 * @returns {bool} Renvoie l'état du test
 */
function telCheck(telRegex) {
  let telValue = document.getElementById("tel").value; // variable du numéro de tel
  telValue = telValue.trim();
  let telValidity = false;

  if (telValue.length == 0) {
    telValidity = true;
    document.getElementById("tel").classList.remove("is-invalid");
  } else {
    if (telRegex.test(telValue)) {
      telValidity = true;
      document.getElementById("tel").classList.remove("is-invalid");
    } else {
      telValidity = false;
      document.getElementById("tel").classList.add("is-invalid");
    }
  }

  return telValidity;
}

/** Vérification du mail:
 * * Vérification de la syntaxe de l'adresse
 * * Si le test n'est pas bon, le champ passe en rouge
 * @param {string} mailRegEx Liste des caractères du regex
 * @returns {bool} Renvoie l'état du test
 */
function mailCheck(mailRegex) {
  let mailValue = document.getElementById("mail").value; // variable d'adresse e-mail
  mailValue = mailValue.trim();
  let mailValidity = false;

  if (mailValue.length == 0) {
    mailValidity = true;
    document.getElementById("mail").classList.remove("is-invalid");
  } else {
    if (mailRegex.test(mailValue)) {
      mailValidity = true;
      document.getElementById("mail").classList.remove("is-invalid");
    } else {
      mailValidity = false;
      document.getElementById("mail").classList.add("is-invalid");
    }
  }

  return mailValidity;
}

/** Vérification de l'url de page perso:
 * * Vérification de la syntaxe de l'adresse
 * * Si le test n'est pas bon, ajout du message d'erreur et le champ passe en rouge
 * @param {string} urlRegex Liste des caractères du regex
 * @returns {bool} Renvoie l'état du test
 */
function webPageCheck(urlRegex) {
  let webpageValue = document.getElementById("webpage").value;
  webpageValue = webpageValue.trim();
  let webPageValidity = false;

  if (webpageValue.length == 0) {
    webPageValidity = true;
    document.getElementById("webpage").classList.remove("is-invalid");
  } else {
    if (urlRegex.test(webpageValue)) {
      webPageValidity = true;
      document.getElementById("webpage").classList.remove("is-invalid");
    } else {
      webPageValidity = false;
      document.getElementById("webpage").classList.add("is-invalid");
    }
  }

  return webPageValidity;
}

/** Vérification de l'url de page doctolib:
 * * Vérification de la syntaxe de l'adresse
 * * Si le test n'est pas bon, ajout du message d'erreur et le champ passe en rouge
 * @param {string} urlRegex Liste des caractères du regex
 * @returns {bool} Renvoie l'état du test
 */
function doctolibPageCheck(urlRegex) {
  let doctolibPageValue = document.getElementById("doctolibpage").value;
  doctolibPageValue = doctolibPageValue.trim();
  let doctolibPageValidity = false;

  if (doctolibPageValue.length == 0) {
    doctolibPageValidity = true;
    document.getElementById("doctolibpage").classList.remove("is-invalid");
  } else {
    if (urlRegex.test(doctolibPageValue)) {
      doctolibPageValidity = true;
      document.getElementById("doctolibpage").classList.remove("is-invalid");
    } else {
      doctolibPageValidity = false;
      document.getElementById("doctolibpage").classList.add("is-invalid");
    }
  }

  return doctolibPageValidity;
}

function resetForm() {
  document.getElementById("lastname").classList.remove("is-invalid");
  document.getElementById("firstname").classList.remove("is-invalid");
  document.getElementById("tel").classList.remove("is-invalid");
  document.getElementById("mail").classList.remove("is-invalid");
  document.getElementById("webpage").classList.remove("is-invalid");
  document.getElementById("doctolibpage").classList.remove("is-invalid");

  document.getElementById("docForm").reset();
}

function submitForm() {
  let formValidity = [];
  formValidity = formChecks();

  let validityStatus = formValidity.findIndex(formValidityArrayChecker);

  if (validityStatus == -1) {
    document.getElementById("docForm").submit();
  }
}

/** Si un seul élements de formValidity est false, la valeur renvoyée sera différente de -1 */
function formValidityArrayChecker(value) {
  return value == false;
}
