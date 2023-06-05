var __read = (this && this.__read) || function (o, n) {
    var m = typeof Symbol === "function" && o[Symbol.iterator];
    if (!m) return o;
    var i = m.call(o), r, ar = [], e;
    try {
        while ((n === void 0 || n-- > 0) && !(r = i.next()).done) ar.push(r.value);
    }
    catch (error) { e = { error: error }; }
    finally {
        try {
            if (r && !r.done && (m = i["return"])) m.call(i);
        }
        finally { if (e) throw e.error; }
    }
    return ar;
};
import pwdRegex from '../../../services/regexStore/pwdRegex.js';
import _ from 'lodash';
export default function pwdFormBehaviour() {
    var formObj = {
        form: document.getElementById('user_account_pwd_form'),
        checkedInputs: {
            pwd: {
                htmlElement: document.getElementById('pwd'),
                checkFoundQuantities: {
                    length: 0,
                    lower: 0,
                    upper: 0,
                    nbr: 0,
                    spe: 0
                },
                checksVerdicts: {
                    length: false,
                    lower: false,
                    upper: false,
                    nbr: false,
                    spe: false
                },
                overallValidityVerdict: false
            },
            confPwd: {
                htmlElement: document.getElementById('confPwd'),
                checkFoundQuantities: {
                    length: 0,
                    lower: 0,
                    upper: 0,
                    nbr: 0,
                    spe: 0
                },
                checksVerdicts: {
                    length: false,
                    lower: false,
                    upper: false,
                    nbr: false,
                    spe: false
                },
                overallValidityVerdict: false
            }
        },
        minimalQtyCriterias: {
            length: 8,
            lower: 1,
            upper: 1,
            nbr: 1,
            spe: 1
        },
        comparisonMsg: {
            htmlElement: document.getElementById('samePwd'),
            inputsAreIdentical: false
        },
        buttons: {
            formReset: document.getElementById('formResetButton'),
            formSubmit: document.getElementById('formSubmitButton')
        }
    };
    fieldInputsEventListeners();
    formButtonsEventListeners();
    /** Listeners pour les champs du form quand on change leur contenu avec un debounce, sert à lancer la vérification des champs
     */
    function fieldInputsEventListeners() {
        formObj.checkedInputs.pwd.htmlElement.addEventListener('input', _.debounce(formChecksManager, 500));
        formObj.checkedInputs.confPwd.htmlElement.addEventListener('input', _.debounce(formChecksManager, 500));
    }
    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    function formButtonsEventListeners() {
        formObj.buttons.formReset.addEventListener('click', resetForm);
        formObj.buttons.formSubmit.addEventListener('click', submitForm);
    }
    /** Série de vérifications des champs du formulaire
     */
    function formChecksManager(evt) {
        var event = evt;
        var inputID = event.target.id;
        formObj.checkedInputs[inputID].checkFoundQuantities = pwdRegex(formObj.checkedInputs[inputID].htmlElement.value);
        formObj.checkedInputs[inputID].checksVerdicts.length = formObj.checkedInputs[inputID].checkFoundQuantities.length >= 8 ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.lower = formObj.checkedInputs[inputID].checkFoundQuantities.lower >= 1 ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.upper = formObj.checkedInputs[inputID].checkFoundQuantities.upper >= 1 ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.nbr = formObj.checkedInputs[inputID].checkFoundQuantities.nbr >= 1 ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.spe = formObj.checkedInputs[inputID].checkFoundQuantities.spe >= 1 ? true : false;
        var gatheredChecksArr = [];
        Object.entries(formObj.checkedInputs[inputID].checksVerdicts).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            gatheredChecksArr.push(value);
        });
        formObj.checkedInputs[inputID].overallValidityVerdict = gatheredChecksArr.includes(false) ? false : true;
        if (formObj.checkedInputs[inputID].overallValidityVerdict) {
            formObj.checkedInputs[inputID].htmlElement.classList.remove('is-invalid');
        }
        else {
            formObj.checkedInputs[inputID].htmlElement.classList.add('is-invalid');
        }
        identicalPwdManager();
    }
    /** Vérifie si les 2 mots de passe sont identiques
     * Ne se fait que si les 2 champs ne sont pas vides
     */
    function identicalPwdManager() {
        var readyForComparison = ((formObj.checkedInputs.pwd.htmlElement.value.length != 0) &&
            (formObj.checkedInputs.confPwd.htmlElement.value.length != 0)) ? true : false;
        if (readyForComparison == false) {
            formObj.comparisonMsg.htmlElement.classList.remove('is-invalid');
            formObj.comparisonMsg.inputsAreIdentical = false;
        }
        else {
            if (formObj.checkedInputs.pwd.htmlElement.value == formObj.checkedInputs.confPwd.htmlElement.value) {
                formObj.comparisonMsg.htmlElement.classList.remove('is-invalid');
                formObj.comparisonMsg.inputsAreIdentical = true;
            }
            else {
                formObj.comparisonMsg.htmlElement.classList.add('is-invalid');
                formObj.comparisonMsg.inputsAreIdentical = false;
            }
        }
    }
    /** Reset du form et des classes des champs inputs
     */
    function resetForm() {
        formObj.checkedInputs.pwdInput.htmlElement.classList.remove('is-invalid');
        formObj.checkedInputs.confPwdInput.htmlElement.classList.remove('is-invalid');
        formObj.comparisonMsg.htmlElement.classList.remove('is-invalid');
        formObj.form.reset();
    }
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    function submitForm() {
        if (formObj.checkedInputs.pwd.overallValidityVerdict && formObj.checkedInputs.confPwd.overallValidityVerdict) {
            formObj.form.submit();
        }
    }
}
