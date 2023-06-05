var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (g && (g = 0, op[0] && (_ = 0)), _) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
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
import fetchDataTransfer from '../../../services/fetchAPI.js';
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
    var feedbackFromBackend = {
        checkedInputs: {
            pwd: {
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
        feedbackFromDB: 'aborted',
        pwdsAreIdentical: false,
        pwdsAreValid: false
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
        formObj.checkedInputs[inputID].checksVerdicts.length = formObj.checkedInputs[inputID].checkFoundQuantities.length >= formObj.minimalQtyCriterias.length ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.lower = formObj.checkedInputs[inputID].checkFoundQuantities.lower >= formObj.minimalQtyCriterias.lower ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.upper = formObj.checkedInputs[inputID].checkFoundQuantities.upper >= formObj.minimalQtyCriterias.upper ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.nbr = formObj.checkedInputs[inputID].checkFoundQuantities.nbr >= formObj.minimalQtyCriterias.nbr ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.spe = formObj.checkedInputs[inputID].checkFoundQuantities.spe >= formObj.minimalQtyCriterias.spe ? true : false;
        var gatheredChecksArr = [];
        Object.entries(formObj.checkedInputs[inputID].checksVerdicts).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            gatheredChecksArr.push(value);
        });
        formObj.checkedInputs[inputID].overallValidityVerdict = gatheredChecksArr.includes(false) ? false : true;
        fieldClassManagmnt(inputID);
        identicalPwdManager();
    }
    /** Ajout un suppression de la classe montrant le message d'erreur pour chaque champ
     * @param {string} inputID ID du champ impacté
     */
    function fieldClassManagmnt(inputID) {
        if (formObj.checkedInputs[inputID].overallValidityVerdict) {
            formObj.checkedInputs[inputID].htmlElement.classList.remove('is-invalid');
        }
        else {
            formObj.checkedInputs[inputID].htmlElement.classList.add('is-invalid');
        }
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
    /** Permet de lancer la gestion asynchrone du fom, évite un message d'erreur à cause du void
     */
    function submitForm() {
        void submitFormAsyncManagmnt();
    }
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    function submitFormAsyncManagmnt() {
        return __awaiter(this, void 0, void 0, function () {
            var formContent;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        if (!(formObj.checkedInputs.pwd.overallValidityVerdict && formObj.checkedInputs.confPwd.overallValidityVerdict)) return [3 /*break*/, 2];
                        formContent = {
                            pwd: formObj.checkedInputs.pwd.htmlElement.value,
                            confPwd: formObj.checkedInputs.confPwd.htmlElement.value
                        };
                        return [4 /*yield*/, fetchDataTransfer('?controller=userAccountPostAsync&action=pwdModif', formContent)];
                    case 1:
                        feedbackFromBackend = _a.sent();
                        addAndModifyFormFeedback();
                        _a.label = 2;
                    case 2: return [2 /*return*/];
                }
            });
        });
    }
    /** Gestion du feedback du backend pour la creéation et la modif d'un doc
     */
    function addAndModifyFormFeedback() {
        if (feedbackFromBackend.pwdsAreIdentical && feedbackFromBackend.pwdsAreValid) {
            validFormFollowUp();
        }
        else {
            invalidFormFollowUp();
        }
    }
    /** S'il y a un souci sur un des champs on met à jour l'overallValidityVerdict l'état du champ dans
     * formObj.checkedInputs[key].overallValidityVerdict et on fait apparaitre un erreur sur le champ concerné
     */
    function invalidFormFollowUp() {
        Object.entries(formObj.checkedInputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            formObj.checkedInputs[key].overallValidityVerdict = feedbackFromBackend.checkedInputs[key].overallValidityVerdict;
            fieldClassManagmnt(key);
        });
        if (feedbackFromBackend.pwdsAreIdentical) {
            formObj.comparisonMsg.htmlElement.classList.remove('is-invalid');
            formObj.comparisonMsg.inputsAreIdentical = true;
        }
        else {
            formObj.comparisonMsg.htmlElement.classList.add('is-invalid');
            formObj.comparisonMsg.inputsAreIdentical = false;
            formObj.checkedInputs.pwd.htmlElement.classList.add('is-invalid');
            formObj.checkedInputs.confPwd.htmlElement.classList.add('is-invalid');
        }
    }
    /** Cas de figure où tous les champs sont bons
     * On revient sur la page montrant le compte du user
     * Sinon on se contente d'avoir un message dans la console...pour le moment
     */
    function validFormFollowUp() {
        switch (formObj.form.case) {
            case 'accountModif':
                if (feedbackFromBackend['feedbackFromDB'] == 'success') {
                    //window.location.assign(`index.php?controller=userAccount&action=showAccountPage`);
                }
                else {
                    console.log('On a un pépin');
                }
                break;
            default:
                //window.location.assign(`index.php?controller=userAccount&action=showAccountPage`);
                break;
        }
    }
}
