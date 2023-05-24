import * as allInOneAJAX from './allInOneAJAX.js';
import _ from 'lodash';

export default function speMedicDocOfficeForm()
{
    const badgeStoreDiv = document.getElementById("badge_store") as HTMLDivElement; // DIV contenant les badges de spécialités médicales
    const selectElement = document.getElementById("select") as HTMLSelectElement; // SELECT de choix de spé médicales
    const addButton = document.getElementById('add_button') as HTMLButtonElement; // bouton à droite du SELECT

    const actualOfficeStoreDiv = document.getElementById("actual_office_store") as HTMLDivElement;
    const potentialOfficeStoreDiv = document.getElementById("potential_office_store") as HTMLDivElement;

    const submitButton = document.getElementById("submit_button") as HTMLInputElement;
    const cancelButton = document.getElementById("cancel_button") as HTMLInputElement;


    addButton.addEventListener('click', addSpeMedic);
    submitButton.addEventListener('click', formSubmit);
    cancelButton.addEventListener('click', formCancel);




    interface allInOneDataInterf {
        status: number,
        response: {
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
    }

    let allInOneData: allInOneDataInterf; // toutes les données récupérées en AJAX
    let docID = ''; // ID du doc concerné
    let everySpeMedicForDoc: {[name: string]: string}[] = []; // toutes les spé médicales attribuales au doc
    let everyDocOfficesOfUser: {[name: string]: string}[] = []; // toutes les données de tous les doc offices du user
    let everySpeMedicOfAllDocOfficesOfUser: {[name: string]: string}[] = []; // toutes les spé médic de tous les doc offices du user

    // suivi des ID de spécialités médicales et de doc offices
    let actualSpeMedicOfDocArray: string[] = []; // id de spé médic assignées au doc
    let actualOfficesIdArray: string[] = []; // id des doc offices assignés au doc
    let potentialOfficesIdArray: string[] = []; // id de tous les doc offices potentiellement assignables au doc

    // templates d'élements HTML
    let removableSpeMedicBadgeTemplate = ''; // template de tous les badges de spé medic assignés au doc
    let officeCardTemplate = ''; // template de doc office card
    let speMedicBadgeForOfficeCardTemplate = ''; // template des badge de spé medic destinées au doc office cards

    const officeCardStoreObj: {[key: string]: string;} = {}; // contient toutes les doc offices cards préassemblées


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
        allInOneData = await allInOneAJAX.ajaxReceive();
        dataExtractFromPromise();
        initialElementsBuildUp();
    };

    /** Extraction des données du Promise et copie dans les variables necessaires
     */
    function dataExtractFromPromise() {
        docID = allInOneData.response.docID;
        everySpeMedicForDoc = allInOneData.response.everySpeMedicForDoc.pdoResult;
        everyDocOfficesOfUser = allInOneData.response.everyDocOfficesOfUser.pdoResult;
        everySpeMedicOfAllDocOfficesOfUser = allInOneData.response.everySpeMedicOfAllDocOfficesOfUser.pdoResult;
        actualSpeMedicOfDocArray = initialSpeMedicIdExtractor(allInOneData.response.speMedicOfDoc.pdoResult);
        actualOfficesIdArray = initialDocOfficeIdExtractor(allInOneData.response.docOfficesOfDoc.pdoResult);

        // récupération des templates HTML
        removableSpeMedicBadgeTemplate = allInOneData.response.removableSpeMedicBadgeTemplate;
        officeCardTemplate = allInOneData.response.officeCardTemplate;
        speMedicBadgeForOfficeCardTemplate = allInOneData.response.speMedicBadgeForOfficeCardTemplate;
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
        speMedicBadgeBuilder();
        speMedicSelectBuilder();
        buttonsAbilityCheck();
        officeCardsMngmt();
    }


    /** Création de tous les cards de doc office pour les stocker dans officeCardStoreObj
     */
    function docOfficeCardStoreBuilder() {
        everyDocOfficesOfUser.forEach( (value: {[name: string]: string;}) => {
            const badgesHTML = speMedicBadgeForOfficeCardBuilder(value.docOfficeID);

            let tempCard = officeCardTemplate;
            tempCard = tempCard.replace('{docOfficeID}', value.docOfficeID);
            tempCard = tempCard.replace('{name}', value.name);
            tempCard = tempCard.replace('{cityName}', value.cityName);
            tempCard = tempCard.replace('{badgesHTML}', badgesHTML);

            const key = value.docOfficeID + "_office";
            officeCardStoreObj[key] = tempCard; // ajout d'un caractére à la fin de la clé pour qu'elle soit une string et qu'elle garde le bon ordre
        });
    }


    /** Construction des badges de spe medic à destination des cards de doc office
     * @param docOfficeID
     * @returns
     */
    function speMedicBadgeForOfficeCardBuilder(docOfficeID: string): string {
        let badgesHTML = '';

        everySpeMedicOfAllDocOfficesOfUser.forEach((value: {[name: string]: string;}) => {
            if (value.docOfficeID == docOfficeID) {
                let tempBadge = speMedicBadgeForOfficeCardTemplate;
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
        badgeStoreDiv.innerHTML = '';

        if (actualSpeMedicOfDocArray.length == 0) {
            badgeStoreDiv.insertAdjacentHTML("beforeend", '<p>Pas de spécialité médicale sélectionnée</p>');
        } else {
            everySpeMedicForDoc.forEach((everySpe: {[name: string]: string;}) => {
                if (actualSpeMedicOfDocArray.includes(everySpe.speMedicID)) {
                    let tempBadge = removableSpeMedicBadgeTemplate;
                    tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID); // utiliser replaceAll() obligerait à passer en lib ES2021
                    tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID); // utiliser replaceAll() obligerait à passer en lib ES2021
                    tempBadge = tempBadge.replace('{speName}', everySpe.nameForDoc);
                    badgeStoreDiv.insertAdjacentHTML("beforeend", tempBadge);

                    const badge = document.getElementById(everySpe.speMedicID + '_spe') as HTMLSpanElement;
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
        selectElement.innerHTML = '';

        everySpeMedicForDoc.forEach((spe: { [name: string]: string; }) => {
            if (actualSpeMedicOfDocArray.includes(spe.speMedicID) == false) {
                const optionElement = document.createElement("option");
                optionElement.value = spe.speMedicID;
                optionElement.text = spe.nameForDoc;
                selectElement.add(optionElement);
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
        _.pull(actualSpeMedicOfDocArray, badgeId);
        badge.removeEventListener("click", speBadgeRemover);
        speMedicBadgeBuilder();
        speMedicSelectBuilder();
        officeCardsMngmt();
    }


    /** Ajout de l'ID de la spé medic à ajouter au actualSpeMedicOfDocArray puis recréation des badges et des Options du Select
     */
    function addSpeMedic() {
        actualSpeMedicOfDocArray.push(selectElement.value);
        speMedicBadgeBuilder();
        speMedicSelectBuilder();
        officeCardsMngmt();
    }


    /** Vérification du nombre de spe medic badges, arrivé à 5 on désactive le bouton d'ajout de spe medic
     */
    function buttonsAbilityCheck() {
        if (actualSpeMedicOfDocArray.length < 5) {
            addButton.disabled = false;
        } else {
            addButton.disabled = true;
        }

        if (actualSpeMedicOfDocArray.length == 0) {
            submitButton.disabled = true;
        } else {
            submitButton.disabled = false;
        }
    }


    /** Gestion des arrays dédiés aux cards de doc office et à leur effacement dans le HTML avant de les redessiner
     */
    function officeCardsMngmt() {
        potentialOfficesIdArray = []; // vidage de potentialOfficesIdArray pour commencer un nouveau cycle

        // ajout de tous les doc offices potentiels dans potentialOfficesIdArray
        everySpeMedicOfAllDocOfficesOfUser.forEach( (value: {[name: string]: string;}) => {
            if (actualSpeMedicOfDocArray.includes(value.speMedicID)) {
                potentialOfficesIdArray.push(value.docOfficeID);
            }
        });

        // suppression des offices déjà assignés au doc de la liste des offices potentiels
        actualOfficesIdArray.forEach( (docOfficeID: string) => {
            if (potentialOfficesIdArray.includes(docOfficeID)) {
                _.pull(potentialOfficesIdArray, docOfficeID);
            }
        });

        // suppression des cards dans actual_office_store et potential_office_store
        actualOfficeStoreDiv.innerHTML = '';
        potentialOfficeStoreDiv.innerHTML = '';

        if (actualOfficesIdArray.length == 0) {
            actualOfficeStoreDiv.insertAdjacentHTML("beforeend", '<p>Aucun cabinet médical assigné</p>');
        } else {
            cardsDrawer('actual', actualOfficesIdArray);
        }

        if (potentialOfficesIdArray.length == 0) {
            potentialOfficeStoreDiv.insertAdjacentHTML("beforeend", '<p>Aucun cabinet médical supplémentaire disponible</p>');
        } else {
            cardsDrawer('potential', potentialOfficesIdArray);
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
        Object.keys(officeCardStoreObj).forEach( (longObjKey: string) => {
            const shortObjKey = longObjKey.replace('_office', '');

            if (officesIdArray.includes(shortObjKey)) {
                if (type == 'actual') {
                    actualOfficeStoreDiv.insertAdjacentHTML("beforeend", officeCardStoreObj[longObjKey]);
                    const cardElement = document.getElementById(longObjKey) as HTMLSpanElement;
                    cardElement.style.cursor = 'pointer';
                    cardElement.addEventListener("click", actualCardClick);
                } else {
                    potentialOfficeStoreDiv.insertAdjacentHTML("beforeend", officeCardStoreObj[longObjKey]);
                    const cardElement = document.getElementById(longObjKey) as HTMLSpanElement;
                    if (actualOfficesIdArray.length < 5) {
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
        _.pull(actualOfficesIdArray, cardId);
        officeCardsMngmt();
    }


    /** Si le user clic sur une card potentielle, son ID est ajoutée à actualOfficesIdArray et on relance l'affichage des cards
     * @param evt
     */
    function potentialCardClick(evt: MouseEvent) {
        const clickedCard = evt.currentTarget as HTMLElement;
        clickedCard.removeEventListener("click", potentialCardClick);
        const cardId = clickedCard.id.replace('_office', '');
        actualOfficesIdArray.push(cardId);
        officeCardsMngmt();
    }


    /** Récupération des données puis mise en forme avant envoyé en POST via AJAX au clic sur le bouton Envoyer
     */
    function formSubmit() {
        // récupération des ID des spé medics confirmées puis transformation
        const confirmedIdsArrayPrep: string[] = [];
        actualSpeMedicOfDocArray.forEach( (id, index) => {
            const template = `speMedicID_${index}=${id}`;
            confirmedIdsArrayPrep.push(template);
        });

        // récupératin des ID des doc offices confirmés puis transformation
        const confirmedDocOfficeIdsArrayPrep: string[] = [];
        actualOfficesIdArray.forEach( (id, index) => {
            const template = `docOfficeID_${index}=${id}`;
            confirmedDocOfficeIdsArrayPrep.push(template);
        });

        const concatenadedArrays = [
            ...confirmedIdsArrayPrep,
            ...confirmedDocOfficeIdsArrayPrep
        ];
        const params = concatenadedArrays.join('&');

        void sendPromise(params);
    }


    /** Actions au clic sur le bouton "Confirmer"
     * Envoie des données via une Promise et redirection vers la page du doc ensuite
     * @param params
     */
    async function sendPromise(params: string) {
        await allInOneAJAX.ajaxSend(params);
        window.location.search = `?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=${docID}`;
    };


    /** Renvoi vers la page du doc au clic sur "Annuler"
     */
    function formCancel() {
        window.location.search = `?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=${docID}`;
    }
}
