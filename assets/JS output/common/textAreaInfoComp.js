"use strict";
window.addEventListener('load', operationsAtLoad);
function operationsAtLoad() {
    if (document.body.contains(document.getElementById('desktop_sidebar'))) {
        textAreaRidonliListenersAddition(); // Pour faire disparaitre "Informations complémentaires" au scroll des textarea
    }
}
/** Ajout d'events liseners sur tous les textareas qui ont la classe 'textarea-ridonli'
 * pour faire disparaitre "Informations complémentaires" au scroll des textareas
 */
function textAreaRidonliListenersAddition() {
    var ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));
    ridonList.forEach(function (element) {
        element.addEventListener('scroll', textAreaScrollDown);
    });
}
/** Disparisation de la phrase 'Informations complémentaires sur l'évènement' quand on scroll down dans les textareas
 * @param {HTMLTextAreaElement} this
 */
function textAreaScrollDown() {
    var label = this.nextElementSibling;
    label.style.opacity = '0';
}
