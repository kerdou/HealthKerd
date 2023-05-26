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
import _ from 'lodash';
export default function userFormBehaviour() {
    var formObj = {
        form: document.getElementById('user_account_form'),
        inputs: {
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
                    isRequired: false,
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
                    isRequired: false,
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
                    isRequired: false,
                    minLengthReq: 7
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
                    isRequired: false,
                    minLengthReq: 7
                },
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
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
    fieldInputsEventListeners();
    formButtonsEventListeners();
    /** Passe de check de tous les checks de champs au démarrage pour mettre à jour formObj.inputs
     */
    function fieldInputsCheckAtStartup() {
        Object.entries(formObj.inputs).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            fieldCheck(key);
        });
    }
    /** Listeners pour les champs du form quand on change leur contenu avec un debounce, sert à lancer la vérification des champs
     */
    function fieldInputsEventListeners() {
        Object.entries(formObj.inputs).forEach(function (_a) {
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
                if (formObj.inputs[fieldID].checksVerdicts.contentValidity) {
                    formObj.inputs[fieldID].checksVerdicts.contentValidity = true;
                    formObj.inputs[fieldID].overallValidityVerdict = true;
                }
                else {
                    formObj.inputs[fieldID].checksVerdicts.contentValidity = false;
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
                formObj.inputs[fieldID].checksVerdicts.contentValidity = nameRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'firstname':
                formObj.inputs[fieldID].checksVerdicts.contentValidity = nameRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'login':
                formObj.inputs[fieldID].checksVerdicts.contentValidity = nameRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'mail':
                formObj.inputs[fieldID].checksVerdicts.contentValidity = mailRegex(formObj.inputs[fieldID].htmlElement.value.trim());
                break;
            case 'birthDate':
                if (frenchDateRegex(formObj.inputs[fieldID].htmlElement.value.trim()) == false) {
                    formObj.inputs[fieldID].checksVerdicts.contentValidity = false;
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
        var splittedDate = formObj.inputs[fieldID].htmlElement.value.trim().split('/');
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
        var dateExists = isExists(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day);
        var dateIsInThePast = isPast(new Date(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day));
        var howManyYears = differenceInYears(new Date(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day), new Date());
        var dateCheckSummary = {
            exists: dateExists,
            isInThePast: dateIsInThePast,
            oldEnough: (howManyYears > 18) ? true : false
        };
        var dateValidityResults = [];
        Object.entries(dateCheckSummary).forEach(function (_a) {
            var _b = __read(_a, 2), key = _b[0], value = _b[1];
            dateValidityResults.push(value);
        });
        var dateIsValid = dateValidityResults.every(function (value, index, arr) {
            return value;
        });
        if (dateIsValid) {
            formObj.inputs[fieldID].checksVerdicts.contentValidity = true;
        }
        else {
            formObj.inputs[fieldID].checksVerdicts.contentValidity = false;
        }
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
