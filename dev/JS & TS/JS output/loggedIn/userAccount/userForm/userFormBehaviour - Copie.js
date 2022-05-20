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
import UserFormChecks from './userFormChecks.js';
import _ from 'lodash';
var UserFormBehaviour = /** @class */ (function (_super) {
    __extends(UserFormBehaviour, _super);
    function UserFormBehaviour() {
        var _this = _super.call(this) || this;
        _this.userForm = document.getElementById('user_account_form');
        _this.lastnameInput = document.getElementById('lastname');
        _this.firstnameInput = document.getElementById('firstname');
        _this.birthDateInput = document.getElementById('birthDate');
        _this.loginInput = document.getElementById('login');
        _this.mailInput = document.getElementById('mail');
        _this.formResetButton = document.getElementById('formResetButton');
        _this.formSubmitButton = document.getElementById('formSubmitButton');
        console.log('UserFormBehaviour');
        _this.fieldInputsEventListeners();
        _this.formButtonsEventListeners();
        return _this;
    }
    /** Listeners pour les champs du form quand ils perdet le focus, sert à lancer la vérification des champs
     */
    UserFormBehaviour.prototype.fieldInputsEventListeners = function () {
        this.lastnameInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.firstnameInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.birthDateInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.loginInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.mailInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
    };
    /** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide
    */
    UserFormBehaviour.prototype.focusOutRecheck = function () {
        this.formChecks();
    };
    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    UserFormBehaviour.prototype.formButtonsEventListeners = function () {
        this.formResetButton.addEventListener('click', this.resetForm.bind(this));
        this.formSubmitButton.addEventListener('click', this.submitForm.bind(this));
    };
    /** Reset du form et des classes des champs inputs
     */
    UserFormBehaviour.prototype.resetForm = function () {
        this.lastnameInput.classList.remove('is-invalid');
        this.firstnameInput.classList.remove('is-invalid');
        this.birthDateInput.classList.remove('is-invalid');
        this.loginInput.classList.remove('is-invalid');
        this.mailInput.classList.remove('is-invalid');
        this.userForm.reset();
    };
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    UserFormBehaviour.prototype.submitForm = function () {
        var formValidityResult = {};
        formValidityResult = this.formChecks();
        var formHasIssues = _.includes(formValidityResult, false);
        // Si aucun test ne renvoie false, on peut submit le form
        if (!formHasIssues) {
            this.userForm.submit();
        }
    };
    /** Série de vérifications des champs du formulaire
     * @returns {object} Renvoie du statut de vérification du formulaire
     */
    UserFormBehaviour.prototype.formChecks = function () {
        /*
        let formValidity = {
            lastNameCheck: false,
            firstNameCheck: false,
            telCheck: false,
            mailCheck: false,
            webPageCheck: false,
            doctolibPageCheck: false
        };

        formValidity.lastNameCheck = super.lastNameCheck();
        formValidity.firstNameCheck = super.firstNameCheck();
        formValidity.telCheck = super.telCheck();
        formValidity.mailCheck = super.mailCheck();
        formValidity.webPageCheck = super.webPageCheck();
        formValidity.doctolibPageCheck = super.doctolibPageCheck();
        */
        var formValidity = {
            toutEstBon: true
        };
        return formValidity;
    };
    return UserFormBehaviour;
}(UserFormChecks));
export default UserFormBehaviour;
