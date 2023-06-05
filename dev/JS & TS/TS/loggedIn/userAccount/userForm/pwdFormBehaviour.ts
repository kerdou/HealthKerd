import pwdRegex from '../../../services/regexStore/pwdRegex.js';
import fetchDataTransfer from '../../../services/fetchAPI.js';
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


    interface feedbackFromBackendInterf {
        checkedInputs: {
            [key: string]: {
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
        feedbackFromDB: string,
        pwdsAreIdentical: boolean,
        pwdsAreValid: boolean
    }

    let feedbackFromBackend: feedbackFromBackendInterf = {
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
    function formChecksManager(evt: Event) {
        const event = evt as KeyboardEvent;
        const inputID = (event.target as HTMLInputElement).id;

        formObj.checkedInputs[inputID].checkFoundQuantities = pwdRegex(formObj.checkedInputs[inputID].htmlElement.value);

        formObj.checkedInputs[inputID].checksVerdicts.length = formObj.checkedInputs[inputID].checkFoundQuantities.length >= formObj.minimalQtyCriterias.length ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.lower = formObj.checkedInputs[inputID].checkFoundQuantities.lower >= formObj.minimalQtyCriterias.lower ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.upper = formObj.checkedInputs[inputID].checkFoundQuantities.upper >= formObj.minimalQtyCriterias.upper ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.nbr = formObj.checkedInputs[inputID].checkFoundQuantities.nbr >= formObj.minimalQtyCriterias.nbr ? true : false;
        formObj.checkedInputs[inputID].checksVerdicts.spe = formObj.checkedInputs[inputID].checkFoundQuantities.spe >= formObj.minimalQtyCriterias.spe ? true : false;

        const gatheredChecksArr: boolean[] = [];

        Object.entries(formObj.checkedInputs[inputID].checksVerdicts).forEach(([key, value]) => {
            gatheredChecksArr.push(value);
        });

        formObj.checkedInputs[inputID].overallValidityVerdict = gatheredChecksArr.includes(false) ? false : true;

        fieldClassManagmnt(inputID);
        identicalPwdManager();
    }


    /** Ajout un suppression de la classe montrant le message d'erreur pour chaque champ
     * @param {string} inputID ID du champ impacté
     */
    function fieldClassManagmnt(inputID: string) {
        if (formObj.checkedInputs[inputID].overallValidityVerdict) {
            formObj.checkedInputs[inputID].htmlElement.classList.remove('is-invalid');
        } else {
            formObj.checkedInputs[inputID].htmlElement.classList.add('is-invalid');
        }
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


    /** Permet de lancer la gestion asynchrone du fom, évite un message d'erreur à cause du void
     */
    function submitForm() {
        void submitFormAsyncManagmnt();
    }


    /** Comportement lors de l'appui sur le bouton de Submit
     */
    async function submitFormAsyncManagmnt() {
        if (formObj.checkedInputs.pwd.overallValidityVerdict && formObj.checkedInputs.confPwd.overallValidityVerdict) {
            const formContent = {
                pwd: formObj.checkedInputs.pwd.htmlElement.value,
                confPwd: formObj.checkedInputs.confPwd.htmlElement.value
            };

            feedbackFromBackend = await fetchDataTransfer('?controller=userAccountPostAsync&action=pwdModif', formContent);
            addAndModifyFormFeedback();
        }
    }


    /** Gestion du feedback du backend pour la creéation et la modif d'un doc
     */
    function addAndModifyFormFeedback() {
        if (feedbackFromBackend.pwdsAreIdentical && feedbackFromBackend.pwdsAreValid) {
            validFormFollowUp();
        } else {
            invalidFormFollowUp();
        }
    }


    /** S'il y a un souci sur un des champs on met à jour l'overallValidityVerdict l'état du champ dans
     * formObj.checkedInputs[key].overallValidityVerdict et on fait apparaitre un erreur sur le champ concerné
     */
    function invalidFormFollowUp() {
        Object.entries(formObj.checkedInputs).forEach(([key, value]) => {
            formObj.checkedInputs[key].overallValidityVerdict = feedbackFromBackend.checkedInputs[key].overallValidityVerdict;
            fieldClassManagmnt(key);
        });

        if (feedbackFromBackend.pwdsAreIdentical) {
            formObj.comparisonMsg.htmlElement.classList.remove('is-invalid');
            formObj.comparisonMsg.inputsAreIdentical = true;
        } else {
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
                } else {
                    console.log('On a un pépin');
                }
                break;

            default:
                //window.location.assign(`index.php?controller=userAccount&action=showAccountPage`);
                break;
        }
    }
}
