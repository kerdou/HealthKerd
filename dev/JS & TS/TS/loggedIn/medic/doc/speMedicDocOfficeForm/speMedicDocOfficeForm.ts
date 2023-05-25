import fetchDataTransfer from '../../../../services/fetchAPI.js';
import _ from 'lodash';

export default function speMedicDocOfficeForm()
{
    interface formObjInterf {
        htmlTemplates: {
            clickableSpeMedicBadge: string,
            officeCard: string,
            speMedicBadgeForOfficeCard: string
        },
        fullOfficeCardsStore: {
            store: {[key: string]: string}
        },
        sections: {
            speMedics: {
                clickableBadges: {
                    htmlElement: HTMLDivElement,
                    array: string[]
                },
                speMedicSelect: {
                    htmlElement: HTMLSelectElement
                },
                speMedicSelectAddButton: {
                    htmlElement: HTMLButtonElement
                }
            },
            docOffice: {
                assignedOffices: {
                    htmlElement: HTMLDivElement,
                    array: string[]
                },
                potentialOffices: {
                    htmlElement: HTMLDivElement,
                    array: string[]
                }
            },
            bottom: {
                submitButton: {
                    htmlElement: HTMLInputElement
                },
                cancelButton: {
                    htmlElement: HTMLInputElement
                }
            }
        },
        data: {
            thisDoc: {
                docID: string
            },
            allDocs: {
                fullSpeMedicList: {[name: string]: string}[]
            },
            userData: {
                everyDocOfficesOfUser: {[name: string]: string}[],
                everySpeMedicOfAllDocOfficesOfUser: {[name: string]: string}[]
            }
        }
    }


    const formObj: formObjInterf = {
        htmlTemplates: {
            clickableSpeMedicBadge: '', // template de tous les badges de spé medic assignés au doc
            officeCard: '', // template de doc office card
            speMedicBadgeForOfficeCard: '' // template des badges de spé medic destinées au doc office cards
        },
        fullOfficeCardsStore: {
            store: {} // contient toutes les doc offices cards préassemblées
        },
        sections: {
            speMedics: {
                clickableBadges: {
                    htmlElement: document.getElementById("badge_store") as HTMLDivElement, // DIV contenant les badges de spécialités médicales
                    array: [] // id de spé médic assignées au doc
                },
                speMedicSelect: {
                    htmlElement: document.getElementById("select") as HTMLSelectElement // SELECT de choix de spé médicales
                },
                speMedicSelectAddButton: {
                    htmlElement: document.getElementById('add_button') as HTMLButtonElement // bouton à droite du SELECT
                }
            },
            docOffice: {
                assignedOffices: {
                    htmlElement: document.getElementById("actual_office_store") as HTMLDivElement,
                    array: [] // id des doc offices assignés au doc
                },
                potentialOffices: {
                    htmlElement: document.getElementById("potential_office_store") as HTMLDivElement,
                    array: [] // id de tous les doc offices potentiellement assignables au doc
                }
            },
            bottom: {
                submitButton: {
                    htmlElement: document.getElementById("submit_button") as HTMLInputElement
                },
                cancelButton: {
                    htmlElement: document.getElementById("cancel_button") as HTMLInputElement
                }
            }
        },
        data: {
            thisDoc: {
                docID: '', // ID du doc concerné
            },
            allDocs: {
                fullSpeMedicList: [] // toutes les spé médicales attribuales au doc
            },
            userData: {
                everyDocOfficesOfUser: [], // toutes les données de tous les doc offices du user
                everySpeMedicOfAllDocOfficesOfUser: [] // toutes les spé médic de tous les doc offices du user
            }
        }
    };

    formObj.sections.speMedics.speMedicSelectAddButton.htmlElement.addEventListener('click', addSpeMedic);
    formObj.sections.bottom.submitButton.htmlElement.addEventListener('click', formSubmit);
    formObj.sections.bottom.cancelButton.htmlElement.addEventListener('click', formCancel);


    interface allInOneDataInterf {
        docID: string,
        docOfficesOfDoc: {
            pdoStmt: string,
            pdoResult: {docOfficeID: string}[]
        },
        speMedicOfDoc: {
            pdoStmt: string,
            pdoResult: {speMedicID: string}[]
        },
        everySpeMedicForDoc: {
            pdoStmt: string,
            pdoResult: {speMedicID: string, nameForDoc: string}[]
        },
        everyDocOfficesOfUser: {
            pdoStmt: string,
            pdoResult: {docOfficeID: string, name: string, cityName: string}[]
        },
        everySpeMedicOfAllDocOfficesOfUser: {
            pdoStmt: string,
            pdoResult: {docOfficeID: string, speMedicID: string, name: string}[]
        },
        officeCardTemplate: string,
        removableSpeMedicBadgeTemplate: string,
        speMedicBadgeForOfficeCardTemplate: string

    }
    let allInOneData: allInOneDataInterf; // toutes les données récupérées en AJAX


    window.addEventListener('load', preLauncher);

    /** Comme on ne peut pas lancer une fonction avec un void depuis un addEventListener,
     * cette fonction a été rajoutée en tant qu'étape intermédiaire
     */
    function preLauncher() {
        void initialBuildUpLauncher(); // Le fait de l'appeler avec l'opérateur void évite l'apparition de messages d'erreur car la Promise n'a pas de Return
    }

    /** Chargement des données depuis une Promise au lancement de la page
     * * Puis extraction des données de la Promise
     * * Puis construction des éléments HTML
     */
    async function initialBuildUpLauncher() {
        allInOneData = await fetchDataTransfer("?controller=medic&subCtrlr=doc&action=getAJAXDataForSpeMedDocOfficeForm", {});
        dataExtractFromPromise();
        initialElementsBuildUp();
    };

    /** Extraction des données du Promise et copie dans les variables necessaires
     */
    function dataExtractFromPromise() {
        formObj.data.thisDoc.docID = allInOneData.docID;
        formObj.data.allDocs.fullSpeMedicList = allInOneData.everySpeMedicForDoc.pdoResult;
        formObj.data.userData.everyDocOfficesOfUser = allInOneData.everyDocOfficesOfUser.pdoResult;
        formObj.data.userData.everySpeMedicOfAllDocOfficesOfUser = allInOneData.everySpeMedicOfAllDocOfficesOfUser.pdoResult;
        formObj.sections.speMedics.clickableBadges.array = initialSpeMedicIdExtractor(allInOneData.speMedicOfDoc.pdoResult);
        formObj.sections.docOffice.assignedOffices.array = initialDocOfficeIdExtractor(allInOneData.docOfficesOfDoc.pdoResult);

        // récupération des templates HTML
        formObj.htmlTemplates.clickableSpeMedicBadge = allInOneData.removableSpeMedicBadgeTemplate;
        formObj.htmlTemplates.officeCard = allInOneData.officeCardTemplate;
        formObj.htmlTemplates.speMedicBadgeForOfficeCard = allInOneData.speMedicBadgeForOfficeCardTemplate;
    }


    /** Extraction des ID de spe medic déjà assignées au doc
     * @param speList
     * @returns
     */
    function initialSpeMedicIdExtractor(speList: {speMedicID: string}[]): string[] {
        const result: string[] = [];

        speList.forEach( (value: {[name: string]: string}) => {
            result.push(value.speMedicID);
        });

        return result;
    }


    /** Extraction des ID de doc offices déjà assignés au doc
     * @param officeList
     * @returns
     */
    function initialDocOfficeIdExtractor(officeList: {docOfficeID: string}[]): string[] {
        const result: string[] = [];

        officeList.forEach( (value: {[name: string]: string}) => {
            result.push(value.docOfficeID);
        });

        return result;
    }


    /** Construction des éléments HTML à partir des données extraites du Promise
    */
    function initialElementsBuildUp() {
        docOfficeCardStoreBuilder();
        formRefreshCycle();
    }


    /** Cycle de mise à jour des éléments suivants:
     * * Badge de spé medic dans Spécialités médicales assignées
     * * Liste du Select de Spécialités médicales disponibles
     * * Activation/désactivation du bouton d'ajout de spé medic sur Select et du bouton d'envoi du Form
     * * Les cards présentes dans Cabinets médicaux assignés et Cabinets médicaux compatibles
     */
    function formRefreshCycle() {
        speMedicBadgeBuilder();
        speMedicSelectBuilder();
        buttonsAbilityCheck();
        officeCardsMngmt();
    }


    /** Création de tous les cards de doc office pour les stocker dans officeCardStoreObj
     */
    function docOfficeCardStoreBuilder() {
        formObj.data.userData.everyDocOfficesOfUser.forEach( (value: {[name: string]: string;}) => {
            const badgesHTML = speMedicBadgeForOfficeCardBuilder(value.docOfficeID);

            let tempCard = formObj.htmlTemplates.officeCard;
            tempCard = tempCard.replace('{docOfficeID}', value.docOfficeID);
            tempCard = tempCard.replace('{name}', value.name);
            tempCard = tempCard.replace('{cityName}', value.cityName);
            tempCard = tempCard.replace('{badgesHTML}', badgesHTML);

            const key = `${value.docOfficeID}_office`;
            formObj.fullOfficeCardsStore.store[key] = tempCard; // ajout d'un caractére à la fin de la clé pour qu'elle soit une string et qu'elle garde le bon ordre
        });
    }


    /** Construction des badges de spe medic à destination des cards de doc office
     * @param docOfficeID
     * @returns
     */
    function speMedicBadgeForOfficeCardBuilder(docOfficeID: string): string {
        let badgesHTML = '';

        formObj.data.userData.everySpeMedicOfAllDocOfficesOfUser.forEach((value: {[name: string]: string;}) => {
            if (value.docOfficeID == docOfficeID) {
                let tempBadge = formObj.htmlTemplates.speMedicBadgeForOfficeCard;
                tempBadge = tempBadge.replace('{speName}', value.name);
                badgesHTML = badgesHTML.concat(tempBadge);
            }
        });

        return badgesHTML;
    }


    /** Cycle de création/recyclage des badges de spé medic
     * * Vidage puis remplissage de la liste de badges de spé médic
     * * Lancement de la gestion des cards de doc office
     */
    function speMedicBadgeBuilder() {
        // vidage puis remplissage de la liste de badges de spé médic
        formObj.sections.speMedics.clickableBadges.htmlElement.innerHTML = '';

        if (formObj.sections.speMedics.clickableBadges.array.length == 0) {
            formObj.sections.speMedics.clickableBadges.htmlElement.insertAdjacentHTML("beforeend", '<p>Pas de spécialité médicale sélectionnée</p>');
        } else {
            formObj.data.allDocs.fullSpeMedicList.forEach((everySpe: {[name: string]: string;}) => {
                if (formObj.sections.speMedics.clickableBadges.array.includes(everySpe.speMedicID)) {
                    let tempBadge = formObj.htmlTemplates.clickableSpeMedicBadge;
                    tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID); // utiliser replaceAll() obligerait à passer en lib ES2021
                    tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID); // utiliser replaceAll() obligerait à passer en lib ES2021
                    tempBadge = tempBadge.replace('{speName}', everySpe.nameForDoc);
                    formObj.sections.speMedics.clickableBadges.htmlElement.insertAdjacentHTML("beforeend", tempBadge);

                    const badge = document.getElementById(`${everySpe.speMedicID}_spe`) as HTMLSpanElement;
                    badge.style.cursor = 'pointer';
                    badge.addEventListener("click", speBadgeRemover);
                }
            });
        }
    }

    /** Cycle de création/recyclage des Options du Select de spé medic
     * * Vidage puis remplissage du SELECT
     * * Lancement de la gestion des cards de doc office
     */
    function speMedicSelectBuilder() {
        // vidage puis remplissage du SELECT
        formObj.sections.speMedics.speMedicSelect.htmlElement.innerHTML = '';

        formObj.data.allDocs.fullSpeMedicList.forEach((spe: { [name: string]: string; }) => {
            if (formObj.sections.speMedics.clickableBadges.array.includes(spe.speMedicID) == false) {
                const optionElement = document.createElement("option");
                optionElement.value = spe.speMedicID;
                optionElement.text = spe.nameForDoc;
                formObj.sections.speMedics.speMedicSelect.htmlElement.add(optionElement);
            }
        });
    }


    /** Suppression d'un badge de spé médicales de doc quand on a clic dessus
     * @param {MouseEvent} evt - Event du clic de souris sur le badge de spé medic du doc
     */
    function speBadgeRemover(evt: MouseEvent) {
        const clickedBadge = evt.currentTarget as HTMLSpanElement;
        const badge = document.getElementById(clickedBadge.id) as HTMLSpanElement;
        const badgeId = badge.id.replace('_spe', '') as string;
        _.pull(formObj.sections.speMedics.clickableBadges.array, badgeId);
        badge.removeEventListener("click", speBadgeRemover);
        formRefreshCycle();
    }


    /** Ajout de l'ID de la spé medic à ajouter au actualSpeMedicOfDocArray puis recréation des badges et des Options du Select
     */
    function addSpeMedic() {
        formObj.sections.speMedics.clickableBadges.array.push(formObj.sections.speMedics.speMedicSelect.htmlElement.value);
        formRefreshCycle();
    }


    /** Vérification du nombre de spe medic badges:
     * * Arrivé à 5 spé médicales on désactive le bouton d'ajout de spe medic du Select
     * * Si aucune spé medic n'est selectionnée on interdit l'envoi du Form
     */
    function buttonsAbilityCheck() {
        // bouton d'ajout de spe medic
        if (formObj.sections.speMedics.clickableBadges.array.length < 5) {
            formObj.sections.speMedics.speMedicSelectAddButton.htmlElement.disabled = false;
        } else {
            formObj.sections.speMedics.speMedicSelectAddButton.htmlElement.disabled = true;
        }

        // bouton d'envoi du form
        if (formObj.sections.speMedics.clickableBadges.array.length == 0) {
            formObj.sections.bottom.submitButton.htmlElement.disabled = true;
        } else {
            formObj.sections.bottom.submitButton.htmlElement.disabled = false;
        }
    }


    /** Gestion des arrays dédiés aux cards de doc office et à leur effacement dans le HTML avant de les redessiner
     */
    function officeCardsMngmt() {
        formObj.sections.docOffice.potentialOffices.array = []; // vidage de potentialOfficesIdArray pour commencer un nouveau cycle

        // ajout de tous les doc offices potentiels dans potentialOfficesIdArray
        formObj.data.userData.everySpeMedicOfAllDocOfficesOfUser.forEach( (value: {[name: string]: string;}) => {
            if (formObj.sections.speMedics.clickableBadges.array.includes(value.speMedicID)) {
                formObj.sections.docOffice.potentialOffices.array.push(value.docOfficeID);
            }
        });

        // suppression des offices déjà assignés au doc de la liste des offices potentiels
        formObj.sections.docOffice.assignedOffices.array.forEach( (docOfficeID: string) => {
            if (formObj.sections.docOffice.potentialOffices.array.includes(docOfficeID)) {
                _.pull(formObj.sections.docOffice.potentialOffices.array, docOfficeID);
            }
        });

        // suppression des cards dans actual_office_store puis recréation des cards
        formObj.sections.docOffice.assignedOffices.htmlElement.innerHTML = '';
        if (formObj.sections.docOffice.assignedOffices.array.length == 0) {
            formObj.sections.docOffice.assignedOffices.htmlElement.insertAdjacentHTML("beforeend", '<p>Aucun cabinet médical assigné</p>');
        } else {
            cardsDrawer('actual', formObj.sections.docOffice.assignedOffices.array);
        }

        // suppression des cards dans potential_office_store puis recréation des cards
        formObj.sections.docOffice.potentialOffices.htmlElement.innerHTML = '';
        if (formObj.sections.docOffice.potentialOffices.array.length == 0) {
            formObj.sections.docOffice.potentialOffices.htmlElement.insertAdjacentHTML("beforeend", '<p>Aucun cabinet médical supplémentaire disponible</p>');
        } else {
            cardsDrawer('potential', formObj.sections.docOffice.potentialOffices.array);
        }
    }


    /** Affichage des cards dans actual_office_store et potential_office_store
     * * On fait un forEach de toutes les cards stockées dans officeCardStoreObj
     * * On change adapte les formats d'ID des doc offices
     * * Si l'ID d'un doc office présent dans officeCardStoreObj est également présent dans officesIdArray on lance son affichage
     * ----
     * * Cette méthode permet de conserver l'ordre d'affichage provenant de la DB qui inclus ORDER BY doc_office_list.cityName, doc_office_list.name
     * @param type - Indique si l'on gére un affichage dans actual_office_store ou dans potential_office_store
     * @param officesIdArray - Liste des ID des doc offices
     */
    function cardsDrawer(type: string, officesIdArray: string[]) {
        Object.keys(formObj.fullOfficeCardsStore.store).forEach( (longObjKey: string) => {
            const shortObjKey = longObjKey.replace('_office', '');

            if (officesIdArray.includes(shortObjKey)) {
                if (type == 'actual') {
                    formObj.sections.docOffice.assignedOffices.htmlElement.insertAdjacentHTML("beforeend", formObj.fullOfficeCardsStore.store[longObjKey]);
                    const cardElement = document.getElementById(longObjKey) as HTMLSpanElement;
                    cardElement.style.cursor = 'pointer';
                    cardElement.addEventListener("click", actualCardClick);
                } else {
                    formObj.sections.docOffice.potentialOffices.htmlElement.insertAdjacentHTML("beforeend", formObj.fullOfficeCardsStore.store[longObjKey]);
                    const cardElement = document.getElementById(longObjKey) as HTMLSpanElement;
                    if (formObj.sections.docOffice.assignedOffices.array.length < 5) {
                        cardElement.style.cursor = 'pointer';
                        cardElement.addEventListener("click", potentialCardClick);
                    }
                }
            }
        });
    }


    /** Si le user clic sur une card assignée, son ID est supprimé de actualOfficesIdArray et on relance l'affichage des cards
     * @param evt
     */
    function actualCardClick(evt: MouseEvent) {
        const clickedCard = evt.currentTarget as HTMLElement;
        clickedCard.removeEventListener("click", actualCardClick);
        const cardId = clickedCard.id.replace('_office', '');
        _.pull(formObj.sections.docOffice.assignedOffices.array, cardId);
        officeCardsMngmt();
    }


    /** Si le user clic sur une card potentielle, son ID est ajoutée à actualOfficesIdArray et on relance l'affichage des cards
     * @param evt
     */
    function potentialCardClick(evt: MouseEvent) {
        const clickedCard = evt.currentTarget as HTMLElement;
        clickedCard.removeEventListener("click", potentialCardClick);
        const cardId = clickedCard.id.replace('_office', '');
        formObj.sections.docOffice.assignedOffices.array.push(cardId);
        officeCardsMngmt();
    }


    /** Récupération des données puis mise en forme avant envoyé en POST via Fetch API au clic sur le bouton Envoyer
     */
    function formSubmit() {
        const confirmedIdsObj: {[key: string]: string} = {};

        formObj.sections.speMedics.clickableBadges.array.forEach( (id, index) => {
            confirmedIdsObj[`speMedicID_${index}`] = id;
        });

        formObj.sections.docOffice.assignedOffices.array.forEach( (id, index) => {
            confirmedIdsObj[`docOfficeID_${index}`] = id;
        });

        void sendFormData(confirmedIdsObj);
    }


    /** Actions au clic sur le bouton "Confirmer"
     * Envoie des données via une Promise et redirection vers la page du doc ensuite
     * @param {[key: string]: string} confirmedIdsObj
     */
    async function sendFormData(confirmedIdsObj: {[key: string]: string}) {
        await fetchDataTransfer("?controller=medicAsync&subCtrlr=docPost&action=editSpeMedDocOfficeForDoc", confirmedIdsObj);
        window.location.search = `?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=${formObj.data.thisDoc.docID}`;
    };


    /** Renvoi vers la page du doc au clic sur "Annuler"
     */
    function formCancel() {
        window.location.search = `?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=${formObj.data.thisDoc.docID}`;
    }
}
