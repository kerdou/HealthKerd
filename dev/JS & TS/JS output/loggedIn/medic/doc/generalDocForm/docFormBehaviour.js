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
import _ from 'lodash';
export default function docFormBehaviour() {
    var formObj = {
        form: document.getElementById('general_doc_form_page'),
        inputs: {
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
        buttons: {
            formReset: document.getElementById('formResetButton'),
            formSubmit: document.getElementById('formSubmitButton')
        }
    };
    fieldInputsCheckAtStartup();
    fieldInputsEventListenersAdd();
    formButtonsEventListeners();
    /** Passe de check de tous les checks de champs au démarrage pour mettre à jour formObj.inputs
     */
    function fieldInputsCheckAtStartup() {
        Object.entries(formObj.inputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            fieldCheck(key);
        });
    }
    /** Listeners pour les champs du form quand un appui de touche est fait
     */
    function fieldInputsEventListenersAdd() {
        Object.entries(formObj.inputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            value.htmlElement.addEventListener('input', _.debounce(fieldCheckOnInput, 500));
        });
        formObj.inputs.tel.htmlElement.addEventListener('keydown', telKeyCheck);
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
        var fieldLength = formObj.inputs[fieldID].htmlElement.value.trim().length;
        if (fieldLength == 0) {
            if (formObj.inputs[fieldID].checkCriterias.isRequired) {
                formObj.inputs[fieldID].checksVerdicts.lengthValidity = false;
                formObj.inputs[fieldID].overallValidityVerdict = false;
            }
            else {
                formObj.inputs[fieldID].checksVerdicts.lengthValidity = true;
                formObj.inputs[fieldID].overallValidityVerdict = true;
            }
        }
        else {
            if (fieldLength < formObj.inputs[fieldID].checkCriterias.minLengthReq) {
                formObj.inputs[fieldID].checksVerdicts.lengthValidity = false;
                formObj.inputs[fieldID].overallValidityVerdict = false;
            }
            else {
                formObj.inputs[fieldID].checksVerdicts.lengthValidity = true;
                regexFieldCheck(fieldID);
                if (formObj.inputs[fieldID].checksVerdicts.regexValidity) {
                    formObj.inputs[fieldID].checksVerdicts.regexValidity = true;
                    formObj.inputs[fieldID].overallValidityVerdict = true;
                }
                else {
                    formObj.inputs[fieldID].checksVerdicts.regexValidity = false;
                    formObj.inputs[fieldID].overallValidityVerdict = false;
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
                formObj.inputs[fieldID].checksVerdicts.regexValidity = nameRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'firstname':
                formObj.inputs[fieldID].checksVerdicts.regexValidity = nameRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'tel':
                formObj.inputs[fieldID].checksVerdicts.regexValidity = telRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'mail':
                formObj.inputs[fieldID].checksVerdicts.regexValidity = mailRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'webpage':
                formObj.inputs[fieldID].checksVerdicts.regexValidity = urlRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'doctolibpage':
                formObj.inputs[fieldID].checksVerdicts.regexValidity = urlRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
        }
        ;
    }
    /** Ajout ou suppression de classe de message d'erreur selon le cas de figure
     * @param {string} fieldID ID du champ concerné
     */
    function fieldClassManagmnt(fieldID) {
        if (formObj.inputs[fieldID].overallValidityVerdict) {
            formObj.inputs[fieldID].htmlElement.classList.remove('is-invalid');
        }
        else {
            formObj.inputs[fieldID].htmlElement.classList.add('is-invalid');
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
        formObj.form.reset();
        Object.entries(formObj.inputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            fieldCheck(key);
            fieldClassManagmnt(key);
        });
    }
    /** Comportement lors de l'appui sur le bouton de Submit
     */
    function submitForm() {
        var formGlobalValidityResults = [];
        Object.entries(formObj.inputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            formGlobalValidityResults.push(value.overallValidityVerdict);
        });
        // si tous les champs sont true, formIsValid sera true, si un seul champ est false, forlisValid sera false
        var formIsValid = formGlobalValidityResults.every(function (value, index, arr) {
            return value;
        });
        if (formIsValid) {
            formObj.form.submit();
        }
    }
}
