var TextAreaInfoComp = /** @class */ (function () {
    function TextAreaInfoComp() {
        this.textAreaRidonliListenersAddition();
    }
    /** Ajout d'events liseners sur tous les textareas qui ont la classe 'textarea-ridonli'
     * pour faire disparaitre "Informations complémentaires" au scroll des textareas
     */
    TextAreaInfoComp.prototype.textAreaRidonliListenersAddition = function () {
        var _this = this;
        var ridonList = Array.from(document.getElementsByClassName('textarea-ridonli'));
        ridonList.forEach(function (element) {
            element.addEventListener('scroll', _this.textAreaScrollDown);
        });
    };
    /** Disparisation de la phrase 'Informations complémentaires sur l'évènement' quand on scroll down dans les textareas
     * @param {HTMLTextAreaElement} this
     */
    TextAreaInfoComp.prototype.textAreaScrollDown = function () {
        var label = this.nextElementSibling;
        label.style.opacity = '0';
    };
    return TextAreaInfoComp;
}());
export default TextAreaInfoComp;
