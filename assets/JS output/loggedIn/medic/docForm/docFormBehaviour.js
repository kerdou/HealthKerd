var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
import DocFormChecks from './docFormChecks.js';
import _ from 'lodash';
var DocFormBehaviour = /** @class */ (function (_super) {
    __extends(DocFormBehaviour, _super);
    function DocFormBehaviour() {
        var _this = _super.call(this) || this;
        _this.docForm = document.getElementById('doc_form_page');
        _this.lastnameInput = document.getElementById('lastname');
        _this.firstnameInput = document.getElementById('firstname');
        _this.telInput = document.getElementById('tel');
        _this.mailInput = document.getElementById('mail');
        _this.webpageInput = document.getElementById('webpage');
        _this.doctolibpageInput = document.getElementById('doctolibpage');
        _this.formResetButton = document.getElementById('formResetButton');
        _this.formSubmitButton = document.getElementById('formSubmitButton');
        _this.fieldInputsFocusOutEventListeners();
        _this.telInputKeyDownEventListener();
        _this.formButtonsEventListeners();
        return _this;
    }
    /** Listeners pour les champs du form quand ils perdet le focus, sert à lancer la vérification des champs
     */
    DocFormBehaviour.prototype.fieldInputsFocusOutEventListeners = function () {
        this.lastnameInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.firstnameInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.telInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.mailInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.webpageInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.doctolibpageInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
    };
    /** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide
    */
    DocFormBehaviour.prototype.focusOutRecheck = function () {
        this.formChecks();
    };
    /** Listener des touches du clavier pour le champ de téléphone
     */
    DocFormBehaviour.prototype.telInputKeyDownEventListener = function () {
        this.telInput.addEventListener('keydown', this.telKeyCheck);
    };
    /** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone
     * @param {KeyboardEvent} event
     */
    DocFormBehaviour.prototype.telKeyCheck = function (event) {
        if (!(event.key >= '0' && event.key <= '9') &&
            event.key != '+' &&
            event.code != 'NumpadAdd' &&
            event.key != '.' &&
            event.code != 'NumpadDecimal' &&
            event.code != 'Backspace' &&
            event.code != 'Delete' &&
            event.code != 'ArrowLeft' &&
            event.code != 'ArrowRight' &&
            event.code != 'Tab') {
            event.preventDefault();
        }
        else if (event.code == 'Space') {
            event.preventDefault();
        }
    };
    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    DocFormBehaviour.prototype.formButtonsEventListeners = function () {
        if (this.formResetButton != null) {
            this.formResetButton.addEventListener('click', this.resetForm.bind(this));
        }
        this.formSubmitButton.addEventListener('click', this.submitForm.bind(this));
    };
    /** Reset du form et des classes des champs inputs
     */
    DocFormBehaviour.prototype.resetForm = function () {
        this.lastnameInput.classList.remove('is-invalid');
        this.firstnameInput.classList.remove('is-invalid');
        this.telInput.classList.remove('is-invalid');
        this.mailInput.classList.remove('is-invalid');
        this.webpageInput.classList.remove('is-invalid');
        this.doctolibpageInput.classList.remove('is-invalid');
        this.docForm.reset();
    };
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    DocFormBehaviour.prototype.submitForm = function () {
        var formValidityResult = {};
        formValidityResult = this.formChecks();
        var formHasIssues = _.includes(formValidityResult, false);
        // Si aucun test ne renvoie false, on peut submit le form
        if (!formHasIssues) {
            this.docForm.submit();
        }
    };
    /** Série de vérifications des champs du formulaire
     * @returns {object} Renvoie du statut de vérification du formulaire
     */
    DocFormBehaviour.prototype.formChecks = function () {
        var formValidity = {
            lastNameCheck: false,
            firstNameCheck: false,
            telCheck: false,
            mailCheck: false,
            webPageCheck: false,
            doctolibPageCheck: false
        };
        formValidity.lastNameCheck = _super.prototype.lastNameCheck.call(this);
        formValidity.firstNameCheck = _super.prototype.firstNameCheck.call(this);
        formValidity.telCheck = _super.prototype.telCheck.call(this);
        formValidity.mailCheck = _super.prototype.mailCheck.call(this);
        formValidity.webPageCheck = _super.prototype.webPageCheck.call(this);
        formValidity.doctolibPageCheck = _super.prototype.doctolibPageCheck.call(this);
        return formValidity;
    };
    return DocFormBehaviour;
}(DocFormChecks));
export default DocFormBehaviour;
