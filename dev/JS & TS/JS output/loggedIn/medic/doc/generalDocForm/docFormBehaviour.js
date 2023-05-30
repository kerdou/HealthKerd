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
import nameRegex from '../../../../services/regexStore/nameRegex.js';
import mailRegex from '../../../../services/regexStore/mailRegex.js';
import telRegex from '../../../../services/regexStore/telRegex.js';
import urlRegex from '../../../../services/regexStore/urlRegex.js';
import fetchDataTransfer from '../../../../services/fetchAPI.js';
import _ from 'lodash';
export default function docFormBehaviour() {
    var formObj = {
        form: {
            htmlElement: document.getElementById('general_doc_form_page'),
            case: ''
        },
        checkedInputs: {
            lastname: {
                htmlElement: document.getElementById('lastname'),
                checkCriterias: {
                    isRequired: true,
                    minLengthReq: 2
                },
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            firstname: {
                htmlElement: document.getElementById('firstname'),
                checkCriterias: {
                    isRequired: false,
                    minLengthReq: 2
                },
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            tel: {
                htmlElement: document.getElementById('tel'),
                checkCriterias: {
                    isRequired: false,
                    minLengthReq: 10
                },
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            mail: {
                htmlElement: document.getElementById('mail'),
                checkCriterias: {
                    isRequired: false,
                    minLengthReq: 7
                },
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            webpage: {
                htmlElement: document.getElementById('webpage'),
                checkCriterias: {
                    isRequired: false,
                    minLengthReq: 5
                },
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            doctolibpage: {
                htmlElement: document.getElementById('doctolibpage'),
                checkCriterias: {
                    isRequired: false,
                    minLengthReq: 5
                },
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            }
        },
        uncheckedInputs: {
            titlegroup: document.getElementById('titlegroup'),
            comment: document.getElementById('comment')
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
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            firstname: {
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            tel: {
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            mail: {
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            webpage: {
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
            doctolibpage: {
                checksVerdicts: {
                    lengthValidity: false,
                    regexValidity: false
                },
                overallValidityVerdict: false
            },
        },
        feedbackFromDB: 'aborted',
        formIsValid: false,
        newDocID: -1
    };
    fieldInputsCheckAtStartup();
    fieldInputsEventListenersAdd();
    formButtonsEventListeners();
    /** Passe de check de tous les checks de champs au démarrage pour mettre à jour formObj.inputs
     */
    function fieldInputsCheckAtStartup() {
        Object.entries(formObj.checkedInputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            fieldCheck(key);
        });
    }
    /** Listeners pour les champs du form quand un appui de touche est fait
     */
    function fieldInputsEventListenersAdd() {
        Object.entries(formObj.checkedInputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            value.htmlElement.addEventListener('input', _.debounce(fieldCheckOnInput, 500));
        });
        formObj.checkedInputs.tel.htmlElement.addEventListener('keydown', telKeyCheck);
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
                if (formObj.checkedInputs[fieldID].checksVerdicts.regexValidity) {
                    formObj.checkedInputs[fieldID].overallValidityVerdict = true;
                }
                else {
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
            case 'firstname':
                formObj.checkedInputs[fieldID].checksVerdicts.regexValidity = nameRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim());
                break;
            case 'tel':
                formObj.checkedInputs[fieldID].checksVerdicts.regexValidity = telRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim());
                break;
            case 'mail':
                formObj.checkedInputs[fieldID].checksVerdicts.regexValidity = mailRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim());
                break;
            case 'webpage':
            case 'doctolibpage':
                formObj.checkedInputs[fieldID].checksVerdicts.regexValidity = urlRegex(formObj.checkedInputs[fieldID].htmlElement.value.trim());
                break;
        }
        ;
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
    /** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone
     * @param {KeyboardEvent} evt Contient les données par rapport à la touche appuyée
     */
    function telKeyCheck(evt) {
        var event = evt;
        var checksOverall = {};
        // Vérifier si l'input est un chiffre
        var numberPattern = new RegExp(/[0-9]/);
        checksOverall.isNumber = numberPattern.test(event.key);
        // Si ce n'est pas un chiffre, on vérifie si c'est une touche acceptable
        if (checksOverall.isNumber == false) {
            checksOverall.isDot = event.key == '.' ? true : false;
            checksOverall.isNumpadDecimal = event.code == 'NumpadDecimal' ? true : false;
            checksOverall.isPlus = event.key == '+' ? true : false;
            checksOverall.isNumpadAdd = event.code == 'NumpadAdd' ? true : false;
            checksOverall.isBackspace = event.code == 'Backspace' ? true : false;
            checksOverall.isDelete = event.code == 'Delete' ? true : false;
            checksOverall.isArrowLeft = event.code == 'ArrowLeft' ? true : false;
            checksOverall.isArrowRight = event.code == 'ArrowRight' ? true : false;
            checksOverall.isTab = event.code == 'Tab' ? true : false;
            checksOverall.isEnter = event.code == 'Enter' ? true : false;
            checksOverall.isNumEnter = event.code == 'NumpadEnter' ? true : false;
        }
        // Vérifier si au moins un des cas de figure est acceptable
        var isAcceptable = Object.values(checksOverall).some(function (value) {
            return value == true;
        });
        // Si l'input n'est pas acceptable, on empéche l'input
        if (isAcceptable == false) {
            event.preventDefault();
        }
    }
    /** Ajout des events listeners des boutons de reset et de submit
     * * Suivant le cas de figure, le bouton de reset ne sera pas toujours pas, donc on conditionne l'ajout de l'eventListener selon cette présence
     */
    function formButtonsEventListeners() {
        if (formObj.buttons.formReset != null) {
            formObj.buttons.formReset.addEventListener('click', resetForm);
        }
        formObj.buttons.formSubmit.addEventListener('click', submitForm);
    }
    /** Reset du form et des classes des champs inputs
     */
    function resetForm() {
        formObj.form.htmlElement.reset();
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
            var formGlobalValidityResults, formIsValid, formContent, _a;
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
                        if (!formIsValid) return [3 /*break*/, 8];
                        formContent = {
                            title: formObj.uncheckedInputs.titlegroup.querySelector('& > input:checked').id,
                            lastname: formObj.checkedInputs.lastname.htmlElement.value,
                            firstname: formObj.checkedInputs.firstname.htmlElement.value,
                            tel: formObj.checkedInputs.tel.htmlElement.value,
                            mail: formObj.checkedInputs.mail.htmlElement.value,
                            webpage: formObj.checkedInputs.webpage.htmlElement.value,
                            doctolibpage: formObj.checkedInputs.doctolibpage.htmlElement.value,
                            comment: formObj.uncheckedInputs.comment.value
                        };
                        formObj.form.case = formObj.form.htmlElement.getAttribute('action');
                        _a = formObj.form.case;
                        switch (_a) {
                            case 'addDoc': return [3 /*break*/, 1];
                            case 'editGeneralDoc': return [3 /*break*/, 3];
                            case 'removeDoc': return [3 /*break*/, 5];
                        }
                        return [3 /*break*/, 7];
                    case 1: return [4 /*yield*/, fetchDataTransfer('?controller=medicAsync&subCtrlr=docPost&action=addDoc', formContent)];
                    case 2:
                        feedbackFromBackend = _b.sent();
                        addAndModifyFormFeedback();
                        return [3 /*break*/, 8];
                    case 3: return [4 /*yield*/, fetchDataTransfer('?controller=medicAsync&subCtrlr=docPost&action=editGeneralDoc', formContent)];
                    case 4:
                        feedbackFromBackend = _b.sent();
                        addAndModifyFormFeedback();
                        return [3 /*break*/, 8];
                    case 5: return [4 /*yield*/, fetchDataTransfer('?controller=medic&subCtrlr=docPost&action=removeDoc', formContent)];
                    case 6:
                        feedbackFromBackend = _b.sent();
                        removedDocFollowUp();
                        return [3 /*break*/, 8];
                    case 7:
                        window.location.assign("index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp");
                        return [3 /*break*/, 8];
                    case 8: return [2 /*return*/];
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
     * Si l'ajout du doc dans la DB s'est bien passé, on part sur la nouvelle page du donc
     * Sinon on se contente d'avoir un message dans la console...pour le moment
     */
    function validFormFollowUp() {
        switch (formObj.form.case) {
            case 'addDoc':
                if (feedbackFromBackend['feedbackFromDB'] == 'success') {
                    window.location.assign("index.php?controller=medic&subCtrlr=doc&action=showDocEditSpeMedDocOfficeForm&docID=".concat(feedbackFromBackend['newDocID']));
                }
                else {
                    console.log('On a un pépin');
                }
                break;
            case 'editGeneralDoc':
                if (feedbackFromBackend['feedbackFromDB'] == 'success') {
                    var docPagePath = document.getElementById('cancelbutton').getAttribute('href');
                    window.location.assign(docPagePath);
                }
                else {
                    console.log('On a un pépin');
                }
                break;
            default:
                window.location.assign("index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp");
                break;
        }
    }
    /** Si la suppression dans la DB se passe bien, on revient sur la liste des docs
     *  Sinon on se contente d'avoir un message dans la console...pour le moment
     */
    function removedDocFollowUp() {
        if (feedbackFromBackend['feedbackFromDB'] == 'success') {
            window.location.assign("index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp");
        }
        else {
            console.log('On a un pépin');
        }
    }
}
