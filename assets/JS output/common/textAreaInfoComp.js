"use strict";
window.addEventListener('load', operationsAtLoad);
/** Copie du contenu du sidebar dans le off canvas sidebar au chargement de la page
*/
function operationsAtLoad() {
    if (document.body.contains(document.getElementById('desktop_sidebar'))) {
        textAreaRidonliListenersAddition(); // Pour faire disparaitre "Informations complémentaires" au scroll des textarea
    }
}
/** Pour faire disparaitre "Informations complémentaires" au scroll des textarea
 */
function textAreaRidonliListenersAddition() {
    var ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));
    ridonList.forEach(function (element) {
        element.addEventListener('scroll', textAreaScrollDown);
    });
}
/**
 *
 * @param this
 */
function textAreaScrollDown() {
    var label = this.nextElementSibling;
    label.style.opacity = '0';
}
