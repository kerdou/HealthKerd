import PwdRegex from '../../../services/regexStore/pwdRegex.js';
import _ from 'lodash';
var PwdFormChecks = /** @class */ (function () {
    function PwdFormChecks() {
    }
    /** Vérification du nom de famille:
     * * Lancement du regex 'nameRegex'
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    PwdFormChecks.prototype.pwdCheck = function (field) {
        var fieldInput = document.getElementById(field);
        var fieldValue = fieldInput.value;
        fieldValue = fieldValue.trim();
        var fieldValidity = false;
        var results = {
            pwdSummary: {},
            ruleCheck: {}
        };
        if (fieldValue.length == 0) {
            fieldValidity = false;
        }
        else {
            var fieldTestObj = new PwdRegex;
            var pwdSummary = fieldTestObj.pwdRegex(fieldValue);
            var ruleCheck = this.rulesChecker(pwdSummary);
            results.pwdSummary = pwdSummary;
            results.ruleCheck = ruleCheck;
            if (ruleCheck.isValid) {
                fieldValidity = true;
                fieldInput.classList.remove('is-invalid');
            }
            else {
                fieldValidity = false;
                fieldInput.classList.add('is-invalid');
            }
        }
        return results;
    };
    /**
     *
     * @param pwdSummary
     * @returns
     */
    PwdFormChecks.prototype.rulesChecker = function (pwdSummary) {
        var rulesChecks = {
            length: false,
            lower: false,
            upper: false,
            nbr: false,
            spe: false,
            isValid: true
        };
        rulesChecks.length = pwdSummary.length >= 8 ? true : false;
        rulesChecks.lower = pwdSummary.lower >= 1 ? true : false;
        rulesChecks.upper = pwdSummary.upper >= 1 ? true : false;
        rulesChecks.nbr = pwdSummary.nbr >= 1 ? true : false;
        rulesChecks.spe = pwdSummary.spe >= 1 ? true : false;
        rulesChecks.isValid = _.includes(rulesChecks, false) ? false : true;
        return rulesChecks;
    };
    PwdFormChecks.prototype.samePwdCheck = function (pwdCheck, confPwdCheck) {
        var results = {
            bothAreValid: false,
            areIdentical: false
        };
        if ((Object.entries(pwdCheck.ruleCheck).length != 0) &&
            (Object.entries(confPwdCheck.ruleCheck).length != 0)) {
            var pwdInput = document.getElementById('pwd');
            var confPwdInput = document.getElementById('confPwd');
            var samePwdInput = document.getElementById('samePwd');
            if ((pwdInput.value) == (confPwdInput.value)) {
                results.areIdentical = true;
                samePwdInput.classList.remove('is-invalid');
            }
            else {
                results.areIdentical = false;
                samePwdInput.classList.add('is-invalid');
            }
        }
        var pwdStatusIsOk = false;
        var confPwdStatusIsOk = false;
        if (Object.entries(pwdCheck.ruleCheck).length != 0) {
            pwdStatusIsOk = pwdCheck.ruleCheck.isValid;
        }
        if (Object.entries(confPwdCheck.ruleCheck).length != 0) {
            confPwdStatusIsOk = confPwdCheck.ruleCheck.isValid;
        }
        if ((pwdStatusIsOk == true) && (confPwdStatusIsOk == true)) {
            results.bothAreValid = true;
        }
        var formHasIssues = _.includes(results, false); // renvoie TRUE s'il y a un souci
        return formHasIssues;
    };
    return PwdFormChecks;
}());
export default PwdFormChecks;
