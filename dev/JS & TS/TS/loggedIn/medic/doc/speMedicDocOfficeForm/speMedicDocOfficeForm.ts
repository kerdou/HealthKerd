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

    const officeCardStoreObj: {[key: string]: string;} = {}; // contient toutes les doc offices cards préassemblées

    let allInOneData: any = []; // toutes les données récupérées en AJAX
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



    addButton.addEventListener('click', addSpeMedic);
    submitButton.addEventListener('click', formSubmit);
    cancelButton.addEventListener('click', formCancel);

    (async () => {
        allInOneData = await allInOneAJAX.ajaxReceive(); // récupération de toutes les données en AJAX

        //console.log(this.allInOneData.response);
        //let debug = document.getElementById('debug') as HTMLDivElement;
        //debug.innerHTML = this.allInOneData.response;

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

        docOfficeCardStoreBuilder();
        speMedicBadgeBuilder();
    }) ();


    /** Extraction des ID de spe medic déjà assignées au doc
     * @param speList
     * @returns
     */
    function initialSpeMedicIdExtractor(speList: any): string[] {
        const result: string[] = [];

        speList.forEach( (value: {[name: string]: string}, index: number) => {
            result.push(value.speMedicID);
        });

        return result;
    }

    /** Extraction des ID de doc offices déjà assignés au doc
     * @param officeList
     * @returns
     */
    function initialDocOfficeIdExtractor(officeList: any): string[] {
        const result: string[] = [];

        officeList.forEach( (value: {[name: string]: string}, index: number) => {
            result.push(value.docOfficeID);
        });

        return result;
    }

    /** Création de tous les carss de doc office pour les stocker dans officeCardStoreObj
     */
    function docOfficeCardStoreBuilder(): void {
        everyDocOfficesOfUser.forEach( (value: {[name: string]: string;}, index: number) => {
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

        everySpeMedicOfAllDocOfficesOfUser.forEach((value: {[name: string]: string;}, index: number) => {
            if (value.docOfficeID == docOfficeID) {
                let tempBadge = speMedicBadgeForOfficeCardTemplate;
                tempBadge = tempBadge.replace('{speName}', value.name);
                badgesHTML = badgesHTML.concat(tempBadge);
            }
        });

        return badgesHTML;
    }

    /** Cycle de création/recyclage des badges de spé medic et d'Options du Select
     * * Vidage puis remplissage de la liste de badges de spé médic
     * * Vidage puis remplissage du SELECT
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

        buttonsAbilityCheck();
        officeCardsMngmt();
    }

    /** Suppression d'un badge de spé médicales de doc quand on a clic dessus
     * @param {MouseEvent} evt - Event du clic de souris sur le badge de spé medic du doc
     */
    function speBadgeRemover(evt: MouseEvent): void {
        const clickedBadge = evt.currentTarget as HTMLSpanElement;
        const badge = document.getElementById(clickedBadge.id) as HTMLSpanElement;
        badge.removeEventListener("click", speBadgeRemover);
        const badgeId = badge.id.replace('_spe', '') as string;
        _.pull(actualSpeMedicOfDocArray, badgeId);
        speMedicBadgeBuilder();
    }

    /** Ajout de l'ID de la spé medic à ajouter au actualSpeMedicOfDocArray puis recréation des badges et des Options du Select
     */
    function addSpeMedic(): void {
        actualSpeMedicOfDocArray.push(selectElement.value);
        speMedicBadgeBuilder();
    }

    /** Vérification du nombre de spe medic badges, arrivé à 5 on désactive le bouton d'ajout de spe medic
     */
    function buttonsAbilityCheck(): void {
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

    /** Gestion de la gestion des arrays dédiés aux cards de doc office et à leur effacement dans le HTML avant de les redessiner
     */
    function officeCardsMngmt(): void {
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
    function cardsDrawer(type: string, officesIdArray: string[]): void {
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
    function actualCardClick(evt: MouseEvent): void {
        const clickedCard = evt.currentTarget as HTMLElement;
        clickedCard.removeEventListener("click", actualCardClick);
        const cardId = clickedCard.id.replace('_office', '');
        _.pull(actualOfficesIdArray, cardId);
        officeCardsMngmt();
    }

    /** Si le user clic sur une card potentielle, son ID est ajoutée à actualOfficesIdArray et on relance l'affichage des cards
     * @param evt
     */
    function potentialCardClick(evt: MouseEvent): void {
        const clickedCard = evt.currentTarget as HTMLElement;
        clickedCard.removeEventListener("click", potentialCardClick);
        const cardId = clickedCard.id.replace('_office', '');
        actualOfficesIdArray.push(cardId);
        officeCardsMngmt();
    }

    /** Récupération des données puis mise en forme avant envoyé en POST via AJAX au clic sur le bouton Envoyer
     */
    function formSubmit(): void {
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

        (async () => {
            const ajax = await allInOneAJAX.ajaxSend(params);

            //const debug = document.getElementById('debug') as HTMLDivElement;
            //debug.innerHTML = ajax.response;

            if (ajax.status == 200) {
                window.location.search = `?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=${docID}`;
            }
        })();
    }

    /** Renvoi vers la page du doc au clic sur "Annuler"
     */
    function formCancel(): void {
        window.location.search = '?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=' + docID;
    }
}
