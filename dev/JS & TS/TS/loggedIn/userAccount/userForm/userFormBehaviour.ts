import nameRegex from '../../../services/regexStore/nameRegex.js';
import mailRegex from '../../../services/regexStore/mailRegex.js';
import frenchDateRegex from '../../../services/regexStore/dateRegex.js';
import isExists from 'date-fns/isExists';
import isPast from 'date-fns/isPast';
import differenceInYears from 'date-fns/differenceInYears';
import fetchDataTransfer from '../../../services/fetchAPI.js';
import _ from 'lodash';

export default function userFormBehaviour()
{
    interface formObjInterf {
        form: HTMLFormElement,
        checkedInputs: {
            [key: string]: {
                htmlElement: HTMLInputElement,
                checkCriterias: {
                    isRequired: boolean,
                    minLengthReq: number
                },
                checksVerdicts: {
                    lengthValidity: boolean,
                    contentValidity: boolean
                },
                overallValidityVerdict: boolean
            }
        },
        uncheckedInputs: {
            [key: string]: HTMLDivElement
        }
        buttons: {
            [key: string]: HTMLButtonElement
        }
    }


    const formObj: formObjInterf = {
        form: document.getElementById('user_account_form') as HTMLFormElement,
        checkedInputs: {
            lastname: {
                htmlElement: document.getElementById('lastname') as HTMLInputElement,
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
                htmlElement: document.getElementById('firstname') as HTMLInputElement,
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
                htmlElement: document.getElementById('birthDate') as HTMLInputElement,
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
                htmlElement: document.getElementById('login') as HTMLInputElement,
                checkCriterias: {
                    isRequired: true,
                    minLengthReq: 5
                },
                checksVerdicts: {
                    lengthValidity: false,
                    contentValidity: false
                },
                overallValidityVerdict: false
            }   ,
            mail: {
                htmlElement: document.getElementById('mail') as HTMLInputElement,
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
            gender: document.getElementById('gender') as HTMLDivElement
        },
        buttons: {
            formReset: document.getElementById('formResetButton') as HTMLButtonElement,
            formSubmit: document.getElementById('formSubmitButton') as HTMLButtonElement
        }
    };

    interface feedbackFromBackendInterf {
        checkedInputs: {
            [key: string]: {
                checksVerdicts: {
                    lengthValidity: boolean,
                    contentValidity: boolean
                },
                overallValidityVerdict: boolean
            }
        },
        feedbackFromDB: string,
        formIsValid: boolean,
        newUserID: number
    }


    let feedbackFromBackend: feedbackFromBackendInterf = {
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
        Object.entries(formObj.checkedInputs).forEach(([key, value]) => {
            fieldCheck(key);
        });
    }


    /** Listeners pour les champs du form quand on change leur contenu avec un debounce, sert à lancer la vérification des champs
     */
    function fieldInputsEventListeners() {
        Object.entries(formObj.checkedInputs).forEach(([key, value]) => {
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
    function fieldCheckOnInput(evt: Event) {
        const event = evt as KeyboardEvent;
        const fieldID = (event.target as HTMLInputElement).id;
        fieldCheck(fieldID);
        fieldClassManagmnt(fieldID);
    }


    /** Lancement de tous les vérifications d'un seul champ
     * * Vérifie si un champ est obligatoire
     * * La longueur minimum du value
     * * S'il est conforme à la regex
     * @param {string} fieldID ID du champ à vérifier
     */
    function fieldCheck(fieldID: string) {
        const fieldLength = formObj.checkedInputs[fieldID].htmlElement.value.trim().length;

        if (fieldLength == 0) {
            if (formObj.checkedInputs[fieldID].checkCriterias.isRequired) {
                formObj.checkedInputs[fieldID].checksVerdicts.lengthValidity = false;
                formObj.checkedInputs[fieldID].overallValidityVerdict = false;
            } else {
                formObj.checkedInputs[fieldID].checksVerdicts.lengthValidity = true;
                formObj.checkedInputs[fieldID].overallValidityVerdict = true;
            }
        } else {
            if (fieldLength < formObj.checkedInputs[fieldID].checkCriterias.minLengthReq) {
                formObj.checkedInputs[fieldID].checksVerdicts.lengthValidity = false;
                formObj.checkedInputs[fieldID].overallValidityVerdict = false;
            } else {
                formObj.checkedInputs[fieldID].checksVerdicts.lengthValidity = true;
                regexFieldCheck(fieldID);

                if (formObj.checkedInputs[fieldID].checksVerdicts.contentValidity) {
                    formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = true;
                    formObj.checkedInputs[fieldID].overallValidityVerdict = true;
                } else {
                    formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = false;
                    formObj.checkedInputs[fieldID].overallValidityVerdict = false;
                }
            }
        }
    }


    /** Regex du champ et mise à jour du staut de regex
     * @param {string} fieldID ID du champ concerné
     */
    function regexFieldCheck(fieldID: string) {
        switch(fieldID) {
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
                } else {
                    birthDateCheck(fieldID);
                }
                break;
        };
    }

    /** Vérifie si la date de naissance existe en complément du regex
     * * On vérifie également que cette appartient au passé
     * * On vérifie aussi que la personne a plus de 18
     * * Tous les critères doivent être true pour que la date soit acceptée
     * @param fieldID
     */
    function birthDateCheck(fieldID: string) {
        const splittedDate: string[] = formObj.checkedInputs[fieldID].htmlElement.value.trim().split('/');

        const nbrSplittedDate = {
            year: 0,
            month: 0,
            day: 0
        };

        splittedDate.map(
            (value, index) => {
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
            }
        );


        const dateCheckSummary = {
            exists: isExists(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day),
            isInThePast: false,
            oldEnough: false
        };

        // il vaut mieux confirmer que la date existe avant de créer une date potentiellement fausse qui renverrait des erreurs
        if (dateCheckSummary.exists) {
            dateCheckSummary.isInThePast = isPast(
                new Date(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day)
            );

            const howManyYearsDiff = differenceInYears(
                new Date(),
                new Date(nbrSplittedDate.year, nbrSplittedDate.month, nbrSplittedDate.day)
            );

            dateCheckSummary.oldEnough = (howManyYearsDiff > 18) ? true : false;
        }

        const dateValidityResults: boolean[] = [];

        Object.entries(dateCheckSummary).forEach(([key, value]) => {
            dateValidityResults.push(value);
        });

        // si tous les élements de dateCheckSummary et donc de dateValidityResults sont valid, alors dateIsValid sera true. Si un seul est false, dateIsValid sera false
        const dateIsValid = dateValidityResults.every((value, index, arr) => {
            return value;
        });

        if (dateIsValid) {
            formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = true;
        } else {
            formObj.checkedInputs[fieldID].checksVerdicts.contentValidity = false;
        }
    }


    /** Ajout ou suppression de classe de message d'erreur selon le cas de figure
     * @param {string} fieldID ID du champ concerné
     */
    function fieldClassManagmnt(fieldID: string) {
        if (formObj.checkedInputs[fieldID].overallValidityVerdict) {
            formObj.checkedInputs[fieldID].htmlElement.classList.remove('is-invalid');
        } else {
            formObj.checkedInputs[fieldID].htmlElement.classList.add('is-invalid');
        }
    }

    /** Reset du form et des classes des champs inputs
     */
    function resetForm() {
        formObj.form.reset();

        Object.entries(formObj.checkedInputs).forEach(([key, value]) => {
            fieldCheck(key);
            fieldClassManagmnt(key);
        });
    }


    function submitForm() {
        void submitFormAsyncManagmnt();
    }


    /** Comportement lors de l'appui sur le bouton de Submit
     */
    async function submitFormAsyncManagmnt() {
        const formGlobalValidityResults: boolean[] = [];

        Object.entries(formObj.checkedInputs).forEach(([key, value]) => {
            formGlobalValidityResults.push(value.overallValidityVerdict);
        });

        // si tous les champs sont true, formIsValid sera true, si un seul champ est false, forlisValid sera false
        const formIsValid = formGlobalValidityResults.every((value, index, arr) => {
            return value;
        });

        if (formIsValid) {
            const formContent = {
                lastname: formObj.checkedInputs.lastname.htmlElement.value,
                firstname: formObj.checkedInputs.firstname.htmlElement.value,
                gender: (formObj.uncheckedInputs.gender.querySelector('input:checked') as HTMLInputElement).value,
                birthDate: formObj.checkedInputs.birthDate.htmlElement.value,
                login: formObj.checkedInputs.login.htmlElement.value,
                mail: formObj.checkedInputs.mail.htmlElement.value
            };

            const formCase = formObj.form.getAttribute('action') as string;

            switch (formCase) {
                case 'accountModif':
                    feedbackFromBackend = await fetchDataTransfer('?controller=userAccountPostAsync&action=accountModif', formContent);
                    addAndModifyFormFeedback();
                    break;

                default:
                    window.location.assign(`index.php?controller=userAccount&action=showAccountPage`);
                    break;
            }
        }
    }


    /** Gestion du feedback du backend pour la creéation et la modif d'un doc
     */
    function addAndModifyFormFeedback() {
        if (feedbackFromBackend.formIsValid) {
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
    }

    /** Cas de figure où tous les champs sont bons
     * On revient sur la page montrant le compte du user
     * Sinon on se contente d'avoir un message dans la console...pour le moment
     */
    function validFormFollowUp() {
        switch (formObj.form.case) {
            case 'accountModif':
                if (feedbackFromBackend['feedbackFromDB'] == 'success') {
                    window.location.assign(`index.php?controller=userAccount&action=showAccountPage`);
                } else {
                    console.log('On a un pépin');
                }
                break;

            default:
                window.location.assign(`index.php?controller=userAccount&action=showAccountPage`);
                break;
        }
    }


}
