import nameRegex from '../../../../services/regexStore/nameRegex.js';
import mailRegex from '../../../../services/regexStore/mailRegex.js';
import telRegex from '../../../../services/regexStore/telRegex.js';
import urlRegex from '../../../../services/regexStore/urlRegex.js';
import fetchDataTransfer from '../../../../services/fetchAPI.js';
import _ from 'lodash';

export default function docFormBehaviour()
{
    interface formObjInterf {
        form: {
            htmlElement: HTMLFormElement,
            case: string
        },
        checkedInputs: {
            [key: string]: {
                htmlElement: HTMLInputElement,
                checkCriterias: {
                    isRequired: boolean,
                    minLengthReq: number
                },
                checksVerdicts: {
                    lengthValidity: boolean,
                    regexValidity: boolean
                },
                overallValidityVerdict: boolean
            }
        },
        uncheckedInputs: {
            [key: string]:  HTMLDivElement | HTMLTextAreaElement
        },
        buttons: {
            [key: string]: HTMLButtonElement
        }
    }


    const formObj: formObjInterf = {
        form: {
            htmlElement: document.getElementById('general_doc_form_page') as HTMLFormElement,
            case: ''
        },
        checkedInputs: {
            lastname: {
                htmlElement: document.getElementById('lastname') as HTMLInputElement,
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
                htmlElement: document.getElementById('firstname') as HTMLInputElement,
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
                htmlElement: document.getElementById('tel') as HTMLInputElement,
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
                htmlElement: document.getElementById('mail') as HTMLInputElement,
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
                htmlElement: document.getElementById('webpage') as HTMLInputElement,
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
                htmlElement: document.getElementById('doctolibpage') as HTMLInputElement,
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
            titlegroup: document.getElementById('titlegroup') as HTMLDivElement,
            comment: document.getElementById('comment') as HTMLTextAreaElement
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
                    regexValidity: boolean
                },
                overallValidityVerdict: boolean
            }
        },
        feedbackFromDB: string,
        formIsValid: boolean,
        newDocID: number
    }

    let feedbackFromBackend: feedbackFromBackendInterf = {
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
        Object.entries(formObj.checkedInputs).forEach(([key, value]) => {
            fieldCheck(key);
        });
    }


    /** Listeners pour les champs du form quand un appui de touche est fait
     */
    function fieldInputsEventListenersAdd() {
        Object.entries(formObj.checkedInputs).forEach(([key, value]) => {
            value.htmlElement.addEventListener('input', _.debounce(fieldCheckOnInput, 500));
        });

        formObj.checkedInputs.tel.htmlElement.addEventListener('keydown', telKeyCheck);
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

                if (formObj.checkedInputs[fieldID].checksVerdicts.regexValidity) {
                    formObj.checkedInputs[fieldID].overallValidityVerdict = true;
                } else {
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
        };
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


    /** Empeche d'entrer autre chose que des chiffres mais permet l'appui de quelques autres touches dans le champ du téléphone
     * @param {KeyboardEvent} evt Contient les données par rapport à la touche appuyée
     */
    function telKeyCheck(evt: Event) {
        const event = evt as KeyboardEvent;
        const checksOverall: { [index: string]: boolean } = {};

        // Vérifier si l'input est un chiffre
        const numberPattern = new RegExp(/[0-9]/);
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
        const isAcceptable = Object.values(checksOverall).some((value) => {
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
                title: (formObj.uncheckedInputs.titlegroup.querySelector('& > input:checked') as HTMLInputElement).id,
                lastname: formObj.checkedInputs.lastname.htmlElement.value,
                firstname: formObj.checkedInputs.firstname.htmlElement.value,
                tel: formObj.checkedInputs.tel.htmlElement.value,
                mail: formObj.checkedInputs.mail.htmlElement.value,
                webpage: formObj.checkedInputs.webpage.htmlElement.value,
                doctolibpage: formObj.checkedInputs.doctolibpage.htmlElement.value,
                comment: (formObj.uncheckedInputs.comment as HTMLTextAreaElement).value
            };

            formObj.form.case = formObj.form.htmlElement.getAttribute('action') as string;

            switch (formObj.form.case) {
                case 'addDoc':
                    feedbackFromBackend = await fetchDataTransfer('?controller=medicAsync&subCtrlr=docPost&action=addDoc', formContent);
                    addAndModifyFormFeedback();
                    break;

                case 'editGeneralDoc':
                    feedbackFromBackend = await fetchDataTransfer('?controller=medicAsync&subCtrlr=docPost&action=editGeneralDoc', formContent);
                    addAndModifyFormFeedback();
                    break;

                case 'removeDoc':
                    feedbackFromBackend = await fetchDataTransfer('?controller=medic&subCtrlr=docPost&action=removeDoc', formContent);
                    removedDocFollowUp();
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
     * Si l'ajout du doc dans la DB s'est bien passé, on part sur la nouvelle page du donc
     * Sinon on se contente d'avoir un message dans la console...pour le moment
     */
    function validFormFollowUp() {
        switch (formObj.form.case) {
            case 'addDoc':
                if (feedbackFromBackend['feedbackFromDB'] == 'success') {
                    window.location.assign(`index.php?controller=medic&subCtrlr=doc&action=showDocEditSpeMedDocOfficeForm&docID=${feedbackFromBackend['newDocID']}`);
                } else {
                    console.log('On a un pépin');
                }
                break;

            case 'editGeneralDoc':
                if (feedbackFromBackend['feedbackFromDB'] == 'success') {
                    const docPagePath = (document.getElementById('cancelbutton') as HTMLAnchorElement).getAttribute('href');
                    window.location.assign(docPagePath as string);
                } else {
                    console.log('On a un pépin');
                }
                break;
        }
    }


    /** Si la suppression dans la DB se passe bien, on revient sur la liste des docs
     *  Sinon on se contente d'avoir un message dans la console...pour le moment
     */
    function removedDocFollowUp() {
        if (feedbackFromBackend['feedbackFromDB'] == 'success') {
            window.location.assign(`index.php?controller=medic&subCtrlr=doc&action=allDocsListDisp`);
        } else {
            console.log('On a un pépin');
        }
    }
}
