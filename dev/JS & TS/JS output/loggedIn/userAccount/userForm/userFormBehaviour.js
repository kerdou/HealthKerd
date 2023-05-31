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
import nameRegex from '../../../services/regexStore/nameRegex.js';
import mailRegex from '../../../services/regexStore/mailRegex.js';
import frenchDateRegex from '../../../services/regexStore/dateRegex.js';
import isExists from 'date-fns/isExists';
import isPast from 'date-fns/isPast';
import differenceInYears from 'date-fns/differenceInYears';
import fetchDataTransfer from '../../../services/fetchAPI.js';
import _ from 'lodash';
export default function userFormBehaviour() {
    var formObj = {
        form: document.getElementById('user_account_form'),
        checkedInputs: {
            lastname: {
                htmlElement: document.getElementById('lastname'),
                checkCriterias: {
                    isRequired: true,
                    minLengthReq: 2
                },
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            },
            firstname: {
                htmlElement: document.getElementById('firstname'),
                checkCriterias: {
                    isRequired: true,
                    minLengthReq: 2
                },
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            },
            birthDate: {
                htmlElement: document.getElementById('birthDate'),
                checkCriterias: {
                    isRequired: true,
                    minLengthReq: 10
                },
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            },
            login: {
                htmlElement: document.getElementById('login'),
                checkCriterias: {
                    isRequired: true,
                    minLengthReq: 5
                },
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            },
            mail: {
                htmlElement: document.getElementById('mail'),
                checkCriterias: {
                    isRequired: true,
                    minLengthReq: 7
                },
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            }
        },
        uncheckedInputs: {
            gender: document.getElementById('gender')
        },
        buttons: {
            formReset: document.getElementById('formResetButton'),
            formSubmit: document.getElementById('formSubmitButton')
        }
    };
    var feedbackFromBackend = {
        checkedInputs: {
            lastname: {
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            },
            firstname: {
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            },
            birthDate: {
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            },
            login: {
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            },
            mail: {
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            }
        },
        feedbackFromDB: 'aborted',
        formIsValid: false,
        newUserID: -1
    };
    fieldInputsCheckAtStartup();
    fieldInputsEventListeners();
    formButtonsEventListeners();
    /** Passe de check de tous les checks de champs au démarrage pour mettre à jour formObj.inputs
     */
    function fieldInputsCheckAtStartup() {
        Object.entries(formObj.checkedInputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            fieldCheck(key);
        });
    }
    /** Listeners pour les champs du form quand on change leur contenu avec un debounce, sert à lancer la vérification des champs
     */
    function fieldInputsEventListeners() {
        Object.entries(formObj.checkedInputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            value.htmlElement.addEventListener('input', _.debounce(fieldCheckOnInput, 500));
        });
    }
    /** Bloque tous les caractéres sauf les chiffres et quelques touches utiles dans le champ de téléphone
     */
    function formButtonsEventListeners() {
        formObj.buttons.formReset.addEventListener('click', resetForm);
        formObj.buttons.formSubmit.addEventListener('click', submitForm);
    }
    /** Déclenchement des checks grace aux appuis de touches
     * @param {KeyboardEvent} evt   Evenement d'appui de touche
     */
    function fieldCheckOnInput(evt) {
        var event = evt;
        var fieldID = event.target.id;
        fieldCheck(fieldID);
        fieldClassManagmnt(fieldID);
    }
    /** Lancement de tous les vérifications d'un seul champ
     * * Vérifie si un champ est obligatoire
     * * La longueur minimum du value
     * * S'il est conforme à la regex
     * @param {string} fieldID ID du champ à vérifier
     */
    function fieldCheck(fieldID) {
        var fieldLength = formObj.checkedInputs[fieldID].htmlElement.value.trim().length;
        if (fieldLength == 0) {
            if (formObj.checkedInputs[fieldID].checkCriterias.isRequired) {
                formObj.checkedInputs[fieldID].checksVerdicts.lengthValidity = false;
                formObj.checkedInputs[fieldID].overallValidityVerdict = false;
            }
            else {
                formObj.checkedInputs[fieldID].checksVerdicts.lengthValidity = true;
                formObj.checkedInputs[fieldID].overallValidityVerdict = true;
            }
        }
        else {
            if (fieldLength < formObj.checkedInputs[fieldID].checkCriterias.minLengthReq) {
                formObj.checkedInputs[fieldID].checksVerdicts.lengthValidity = false;
                formObj.checkedInputs[fieldID].overallValidityVerdict = false;
            }
            else {
                formObj.checkedInputs[fieldID].checksVerdicts.lengthValidity = true;
                regexFieldCheck(fieldID);
                if (formObj.checkedInputs[fieldID].checksVerdicts.contentValidity) {
                    formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = true;
                    formObj.checkedInputs[fieldID].overallValidityVerdict = true;
                }
                else {
                    formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = false;
                    formObj.checkedInputs[fieldID].overallValidityVerdict = false;
                }
            }
        }
    }
    /** Regex du champ et mise à jour du staut de regex
     * @param {string} fieldID ID du champ concerné
     */
    function regexFieldCheck(fieldID) {
        switch (fieldID) {
            case 'lastname':
                formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = nameRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim());
                break;
            case 'firstname':
                formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = nameRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim());
                break;
            case 'login':
                formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = nameRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim());
                break;
            case 'mail':
                formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = mailRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim());
                break;
            case 'birthDate':
                if (frenchDateRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim()) == false) {
                    formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = false;
                }
                else {
                    birthDateCheck(fieldID);
                }
                break;
        }
        ;
    }
    /** Vérifie si la date de naissance existe en complément du regex
     * * On vérifie également que cette appartient au passé
     * * On vérifie aussi que la personne a plus de 18
     * * Tous les critères doivent être true pour que la date soit acceptée
     * @param fieldID
     */
    function birthDateCheck(fieldID) {
        var splittedDate = formObj.checkedInputs[fieldID].htmlElement.value.trim().split('/');
        var nbrSplittedDate = {
            year: 0,
            month: 0,
            day: 0
        };
        splittedDate.map(function (value, index) {
            switch (index) {
                case 0:
                    nbrSplittedDate.day = +value;
                    break;
                case 1:
                    nbrSplittedDate.month = +value - 1;
                    break;
                case 2:
                    nbrSplittedDate.year = +value;
                    break;
            }
        });
        var dateCheckSummary = {
            exists: isExists(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day),
            isInThePast: false,
            oldEnough: false
        };
        // il vaut mieux confirmer que la date existe avant de créer une date potentiellement fausse qui renverrait des erreurs
        if (dateCheckSummary.exists) {
            dateCheckSummary.isInThePast = isPast(new Date(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day));
            var howManyYearsDiff = differenceInYears(new Date(), new Date(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day));
            dateCheckSummary.oldEnough = (howManyYearsDiff > 18) ? true : false;
        }
        var dateValidityResults = [];
        Object.entries(dateCheckSummary).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            dateValidityResults.push(value);
        });
        // si tous les élements de dateCheckSummary et donc de dateValidityResults sont valid, alors dateIsValid sera true. Si un seul est false, dateIsValid sera false
        var dateIsValid = dateValidityResults.every(function (value, index, arr) {
            return value;
        });
        if (dateIsValid) {
            formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = true;
        }
        else {
            formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = false;
        }
    }
    /** Ajout ou suppression de classe de message d'erreur selon le cas de figure
     * @param {string} fieldID ID du champ concerné
     */
    function fieldClassManagmnt(fieldID) {
        if (formObj.checkedInputs[fieldID].overallValidityVerdict) {
            formObj.checkedInputs[fieldID].htmlElement.classList.remove('is-invalid');
        }
        else {
            formObj.checkedInputs[fieldID].htmlElement.classList.add('is-invalid');
        }
    }
    /** Reset du form et des classes des champs inputs
     */
    function resetForm() {
        formObj.form.reset();
        Object.entries(formObj.checkedInputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            fieldCheck(key);
            fieldClassManagmnt(key);
        });
    }
    function submitForm() {
        void submitFormAsyncManagmnt();
    }
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    function submitFormAsyncManagmnt() {
        return __awaiter(this, void 0, void 0, function () {
            var formGlobalValidityResults, formIsValid, formContent, formCase, _a;
            return __generator(this, function (_b) {
                switch (_b.label) {
                    case 0:
                        formGlobalValidityResults = [];
                        Object.entries(formObj.checkedInputs).forEach(function (_a) {
                            var _b = __read(_a, 2), key = _b[0], value = _b[1];
                            formGlobalValidityResults.push(value.overallValidityVerdict);
                        });
                        formIsValid = formGlobalValidityResults.every(function (value, index, arr) {
                            return value;
                        });
                        if (!formIsValid) return [3 /*break*/, 4];
                        formContent = {
                            lastname: formObj.checkedInputs.lastname.htmlElement.value,
                            firstname: formObj.checkedInputs.firstname.htmlElement.value,
                            gender: formObj.uncheckedInputs.gender.querySelector('input:checked').value,
                            birthDate: formObj.checkedInputs.birthDate.htmlElement.value,
                            login: formObj.checkedInputs.login.htmlElement.value,
                            mail: formObj.checkedInputs.mail.htmlElement.value
                        };
                        formCase = formObj.form.getAttribute('action');
                        _a = formCase;
                        switch (_a) {
                            case 'accountModif': return [3 /*break*/, 1];
                        }
                        return [3 /*break*/, 3];
                    case 1: return [4 /*yield*/, fetchDataTransfer('?controller=userAccountPostAsync&action=accountModif', formContent)];
                    case 2:
                        feedbackFromBackend = _b.sent();
                        addAndModifyFormFeedback();
                        return [3 /*break*/, 4];
                    case 3:
                        window.location.assign("index.php?controller=userAccount&action=showAccountPage");
                        return [3 /*break*/, 4];
                    case 4: return [2 /*return*/];
                }
            });
        });
    }
    /** Gestion du feedback du backend pour la creéation et la modif d'un doc
     */
    function addAndModifyFormFeedback() {
        if (feedbackFromBackend.formIsValid) {
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
    }
    /** Cas de figure où tous les champs sont bons
     * On revient sur la page montrant le compte du user
     * Sinon on se contente d'avoir un message dans la console...pour le moment
     */
    function validFormFollowUp() {
        switch (formObj.form.case) {
            case 'accountModif':
                if (feedbackFromBackend['feedbackFromDB'] == 'success') {
                    window.location.assign("index.php?controller=userAccount&action=showAccountPage");
                }
                else {
                    console.log('On a un pépin');
                }
                break;
            default:
                window.location.assign("index.php?controller=userAccount&action=showAccountPage");
                break;
        }
    }
}
