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
import PwdFormChecks from './pwdFormChecks.js';
import _ from 'lodash';
var PwdFormBehaviour = /** @class */ (function (_super) {
    __extends(PwdFormBehaviour, _super);
    function PwdFormBehaviour() {
        var _this = _super.call(this) || this;
        _this.pwdForm = document.getElementById('user_account_pwd_form');
        _this.pwdInput = document.getElementById('pwd');
        _this.confPwdInput = document.getElementById('confPwd');
        _this.samePwdInput = document.getElementById('samePwd');
        _this.formResetButton = document.getElementById('formResetButton');
        _this.formSubmitButton = document.getElementById('formSubmitButton');
        _this.fieldInputsEventListeners();
        _this.formButtonsEventListeners();
        return _this;
    }
    /** Listeners pour les champs du form quand ils perdet le focus, sert à lancer la vérification des champs
     */
    PwdFormBehaviour.prototype.fieldInputsEventListeners = function () {
        this.pwdInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
        this.confPwdInput.addEventListener('input', _.debounce(this.focusOutRecheck.bind(this), 150));
    };
    /** Relance la vérification des champs quand l'un d'eux perd le focus et qu'il n'est pas vide
    */
    PwdFormBehaviour.prototype.focusOutRecheck = function () {
        this.formChecks();
    };
    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    PwdFormBehaviour.prototype.formButtonsEventListeners = function () {
        this.formResetButton.addEventListener('click', this.resetForm.bind(this));
        this.formSubmitButton.addEventListener('click', this.submitForm.bind(this));
    };
    /** Reset du form et des classes des champs inputs
     */
    PwdFormBehaviour.prototype.resetForm = function () {
        this.pwdInput.classList.remove('is-invalid');
        this.confPwdInput.classList.remove('is-invalid');
        this.samePwdInput.classList.remove('is-invalid');
        this.pwdForm.reset();
    };
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    PwdFormBehaviour.prototype.submitForm = function () {
        var formHasIssues = this.formChecks();
        // Si aucun test ne renvoie false, on peut submit le form
        if (formHasIssues == false) {
            this.pwdForm.submit();
        }
    };
    /** Série de vérifications des champs du formulaire
     * @returns {boolean} Renvoie du statut de vérification du formulaire
     */
    PwdFormBehaviour.prototype.formChecks = function () {
        var pwdCheck = _super.prototype.pwdCheck.call(this, 'pwd');
        var confPwdCheck = _super.prototype.pwdCheck.call(this, 'confPwd');
        var formHasIssues = false;
        formHasIssues = _super.prototype.samePwdCheck.call(this, pwdCheck, confPwdCheck);
        return formHasIssues;
    };
    return PwdFormBehaviour;
}(PwdFormChecks));
export default PwdFormBehaviour;
