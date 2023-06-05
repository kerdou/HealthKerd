import pwdRegex from '../../../services/regexStore/pwdRegex.js';
import _ from 'lodash';

export default function pwdFormBehaviour()
{
    interface formObjInterf {
        form: HTMLFormElement,
        checkedInputs: {
            [key: string]: {
                htmlElement: HTMLInputElement,
                checkFoundQuantities: {
                    length: number,
                    lower: number,
                    upper: number,
                    nbr: number,
                    spe: number
                },
                checksVerdicts: {
                    length: boolean,
                    lower: boolean,
                    upper: boolean,
                    nbr: boolean,
                    spe: boolean
                },
                overallValidityVerdict: boolean
            }
        },
        minimalQtyCriterias: {
            [key: string]: number
        },
        comparisonMsg: {
            htmlElement: HTMLInputElement,
            inputsAreIdentical: boolean
        }
        buttons: {
            [key: string]: HTMLButtonElement
        }
    }


    const formObj: formObjInterf = {
        form: document.getElementById('user_account_pwd_form') as HTMLFormElement,
        checkedInputs: {
            pwd: {
                htmlElement: document.getElementById('pwd') as HTMLInputElement,
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
                htmlElement: document.getElementById('confPwd') as HTMLInputElement,
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
            htmlElement: document.getElementById('samePwd') as HTMLInputElement,
            inputsAreIdentical: false
        },
        buttons: {
            formReset: document.getElementById('formResetButton') as HTMLButtonElement,
            formSubmit: document.getElementById('formSubmitButton') as HTMLButtonElement
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
    function formChecksManager(evt: Event) {
        const event = evt as KeyboardEvent;
        const inputID = (event.target as HTMLInputElement).id;

        formObj.checkedInputs[inputID].checkFoundQuantities = pwdRegex(formObj.checkedInputs[inputID].htmlElement.value);

        formObj.checkedInputs[inputID].checksVerdicts.length = formObj.checkedInputs[inputID].checkFoundQuantities.length >= 8 ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.lower = formObj.checkedInputs[inputID].checkFoundQuantities.lower >= 1 ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.upper = formObj.checkedInputs[inputID].checkFoundQuantities.upper >= 1 ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.nbr = formObj.checkedInputs[inputID].checkFoundQuantities.nbr >= 1 ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.spe = formObj.checkedInputs[inputID].checkFoundQuantities.spe >= 1 ? true : false;

        const gatheredChecksArr: boolean[] = [];

        Object.entries(formObj.checkedInputs[inputID].checksVerdicts).forEach(([key, value]) => {
            gatheredChecksArr.push(value);
        });

        formObj.checkedInputs[inputID].overallValidityVerdict = gatheredChecksArr.includes(false) ? false : true;

        if (formObj.checkedInputs[inputID].overallValidityVerdict) {
            formObj.checkedInputs[inputID].htmlElement.classList.remove('is-invalid');
        } else {
            formObj.checkedInputs[inputID].htmlElement.classList.add('is-invalid');
        }

        identicalPwdManager();
    }


    /** Vérifie si les 2 mots de passe sont identiques
     * Ne se fait que si les 2 champs ne sont pas vides
     */
    function identicalPwdManager() {
        const readyForComparison = (
            (formObj.checkedInputs.pwd.htmlElement.value.length != 0) &&
            (formObj.checkedInputs.confPwd.htmlElement.value.length != 0)
        ) ? true : false;

        if (readyForComparison == false) {
            formObj.comparisonMsg.htmlElement.classList.remove('is-invalid');
            formObj.comparisonMsg.inputsAreIdentical = false;
        } else {
            if (formObj.checkedInputs.pwd.htmlElement.value == formObj.checkedInputs.confPwd.htmlElement.value) {
                formObj.comparisonMsg.htmlElement.classList.remove('is-invalid');
                formObj.comparisonMsg.inputsAreIdentical = true;
            } else {
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
