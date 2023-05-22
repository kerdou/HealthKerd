import allInOneAJAX from './allInOneAJAX.js';
import _ from 'lodash';

export default class SpeMedicDocOfficeForm
{
    private allInOne = new allInOneAJAX;

    private badgeStoreDiv = document.getElementById("badge_store") as HTMLDivElement; // DIV contenant les badges de spécialités médicales
    private selectElement = document.getElementById("select") as HTMLSelectElement; // SELECT de choix de spé médicales
    private addButton = document.getElementById('add_button') as HTMLButtonElement; // bouton à droite du SELECT

    private actualOfficeStoreDiv = document.getElementById("actual_office_store") as HTMLDivElement;
    private potentialOfficeStoreDiv = document.getElementById("potential_office_store") as HTMLDivElement;

    private submitButton = document.getElementById("submit_button") as HTMLInputElement;
    private cancelButton = document.getElementById("cancel_button") as HTMLInputElement;

    private officeCardStoreObj: {[key: string]: string;} = {}; // contient toutes les doc offices cards préassemblées

    private allInOneData: any = []; // toutes les données récupérées en AJAX
    private docID = ''; // ID du doc concerné
    private everySpeMedicForDoc: {[name: string]: string}[] = []; // toutes les spé médicales attribuales au doc
    private everyDocOfficesOfUser: {[name: string]: string}[] = []; // toutes les données de tous les doc offices du user
    private everySpeMedicOfAllDocOfficesOfUser: {[name: string]: string}[] = []; // toutes les spé médic de tous les doc offices du user

    // suivi des ID de spécialités médicales et de doc offices
    private actualSpeMedicOfDocArray: string[] = []; // id de spé médic assignées au doc
    private actualOfficesIdArray: string[] = []; // id des doc offices assignés au doc
    private potentialOfficesIdArray: string[] = []; // id de tous les doc offices potentiellement assignables au doc

    // templates d'élements HTML
    private removableSpeMedicBadgeTemplate = ''; // template de tous les badges de spé medic assignés au doc
    private officeCardTemplate = ''; // template de doc office card
    private speMedicBadgeForOfficeCardTemplate = ''; // template des badge de spé medic destinées au doc office cards


    constructor () {
        this.addButton.addEventListener('click', this.addSpeMedic.bind(this));
        this.submitButton.addEventListener('click', this.formSubmit.bind(this));
        this.cancelButton.addEventListener('click', this.formCancel.bind(this));

        (async () => {
            this.allInOneData = await this.allInOne.receive(); // récupération de toutes les données en AJAX

            //console.log(this.allInOneData.response);
            //let debug = document.getElementById('debug') as HTMLDivElement;
            //debug.innerHTML = this.allInOneData.response;

            this.docID = this.allInOneData.response.docID;
            this.everySpeMedicForDoc = this.allInOneData.response.everySpeMedicForDoc.pdoResult;
            this.everyDocOfficesOfUser = this.allInOneData.response.everyDocOfficesOfUser.pdoResult;
            this.everySpeMedicOfAllDocOfficesOfUser = this.allInOneData.response.everySpeMedicOfAllDocOfficesOfUser.pdoResult;
            this.actualSpeMedicOfDocArray = this.initialSpeMedicIdExtractor(this.allInOneData.response.speMedicOfDoc.pdoResult);
            this.actualOfficesIdArray = this.initialDocOfficeIdExtractor(this.allInOneData.response.docOfficesOfDoc.pdoResult);

            // récupération des templates HTML
            this.removableSpeMedicBadgeTemplate = this.allInOneData.response.removableSpeMedicBadgeTemplate;
            this.officeCardTemplate = this.allInOneData.response.officeCardTemplate;
            this.speMedicBadgeForOfficeCardTemplate = this.allInOneData.response.speMedicBadgeForOfficeCardTemplate;

            this.docOfficeCardStoreBuilder();
            this.speMedicBadgeBuilder();
        }) ();
    }

    /** Extraction des ID de spe medic déjà assignées au doc
     * @param speList
     * @returns
     */
    private initialSpeMedicIdExtractor(speList: any): string[] {
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
    private initialDocOfficeIdExtractor(officeList: any): string[] {
        const result: string[] = [];

        officeList.forEach( (value: {[name: string]: string}, index: number) => {
            result.push(value.docOfficeID);
        });

        return result;
    }

    /** Création de tous les carss de doc office pour les stocker dans officeCardStoreObj
     */
    private docOfficeCardStoreBuilder(): void {
        this.everyDocOfficesOfUser.forEach( (value: {[name: string]: string;}, index: number) => {
            const badgesHTML = this.speMedicBadgeForOfficeCardBuilder(value.docOfficeID);

            let tempCard = this.officeCardTemplate;
            tempCard = tempCard.replace('{docOfficeID}', value.docOfficeID);
            tempCard = tempCard.replace('{name}', value.name);
            tempCard = tempCard.replace('{cityName}', value.cityName);
            tempCard = tempCard.replace('{badgesHTML}', badgesHTML);

            const key = value.docOfficeID + "_office";
            this.officeCardStoreObj[key] = tempCard; // ajout d'un caractére à la fin de la clé pour qu'elle soit une string et qu'elle garde le bon ordre
        });
    }

    /** Construction des badges de spe medic à destination des cards de doc office
     * @param docOfficeID
     * @returns
     */
    private speMedicBadgeForOfficeCardBuilder(docOfficeID: string): string {
        let badgesHTML = '';

        this.everySpeMedicOfAllDocOfficesOfUser.forEach((value: {[name: string]: string;}, index: number) => {
            if (value.docOfficeID == docOfficeID) {
                let tempBadge = this.speMedicBadgeForOfficeCardTemplate;
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
    private speMedicBadgeBuilder() {
        // vidage puis remplissage de la liste de badges de spé médic
        this.badgeStoreDiv.innerHTML = '';

        if (this.actualSpeMedicOfDocArray.length == 0) {
            this.badgeStoreDiv.insertAdjacentHTML("beforeend", '<p>Pas de spécialité médicale sélectionnée</p>');
        } else {

            this.everySpeMedicForDoc.forEach((everySpe: {[name: string]: string;}) => {
                if (this.actualSpeMedicOfDocArray.includes(everySpe.speMedicID)) {
                    let tempBadge = this.removableSpeMedicBadgeTemplate;
                    tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID); // utiliser replaceAll() obligerait à passer en lib ES2021
                    tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID); // utiliser replaceAll() obligerait à passer en lib ES2021
                    tempBadge = tempBadge.replace('{speName}', everySpe.nameForDoc);
                    this.badgeStoreDiv.insertAdjacentHTML("beforeend", tempBadge);

                    const badge = document.getElementById(everySpe.speMedicID + '_spe') as HTMLSpanElement;
                    badge.style.cursor = 'pointer';
                    badge.addEventListener("click", this.speBadgeRemover.bind(this));
                }
            });
        }

        // vidage puis remplissage du SELECT
        this.selectElement.innerHTML = '';

        this.everySpeMedicForDoc.forEach((spe: { [name: string]: string; }) => {
            if (this.actualSpeMedicOfDocArray.includes(spe.speMedicID) == false) {
                const optionElement = document.createElement("option");
                optionElement.value = spe.speMedicID;
                optionElement.text = spe.nameForDoc;
                this.selectElement.add(optionElement);
            }
        });

        this.buttonsAbilityCheck();
        this.officeCardsMngmt();
    }

    /** Suppression d'un badge de spé médicales de doc quand on a clic dessus
     * @param {MouseEvent} evt - Event du clic de souris sur le badge de spé medic du doc
     */
    private speBadgeRemover(evt: MouseEvent): void {
        const clickedBadge = evt.currentTarget as HTMLSpanElement;
        const badge = document.getElementById(clickedBadge.id) as HTMLSpanElement;
        badge.removeEventListener("click", this.speBadgeRemover);
        const badgeId = badge.id.replace('_spe', '') as string;
        _.pull(this.actualSpeMedicOfDocArray, badgeId);
        this.speMedicBadgeBuilder();
    }

    /** Ajout de l'ID de la spé medic à ajouter au actualSpeMedicOfDocArray puis recréation des badges et des Options du Select
     */
    private addSpeMedic(): void {
        this.actualSpeMedicOfDocArray.push(this.selectElement.value);
        this.speMedicBadgeBuilder();
    }

    /** Vérification du nombre de spe medic badges, arrivé à 5 on désactive le bouton d'ajout de spe medic
     */
    private buttonsAbilityCheck(): void {
        if (this.actualSpeMedicOfDocArray.length < 5) {
            this.addButton.disabled = false;
        } else {
            this.addButton.disabled = true;
        }

        if (this.actualSpeMedicOfDocArray.length == 0) {
            this.submitButton.disabled = true;
        } else {
            this.submitButton.disabled = false;
        }
    }

    /** Gestion de la gestion des arrays dédiés aux cards de doc office et à leur effacement dans le HTML avant de les redessiner
     */
    private officeCardsMngmt(): void {
        this.potentialOfficesIdArray = []; // vidage de potentialOfficesIdArray pour commencer un nouveau cycle

        // ajout de tous les doc offices potentiels dans potentialOfficesIdArray
        this.everySpeMedicOfAllDocOfficesOfUser.forEach( (value: {[name: string]: string;}) => {
            if (this.actualSpeMedicOfDocArray.includes(value.speMedicID)) {
                this.potentialOfficesIdArray.push(value.docOfficeID);
            }
        });

        // suppression des offices déjà assignés au doc de la liste des offices potentiels
        this.actualOfficesIdArray.forEach( (docOfficeID: string) => {
            if (this.potentialOfficesIdArray.includes(docOfficeID)) {
                _.pull(this.potentialOfficesIdArray, docOfficeID);
            }
        });

        // suppression des cards dans actual_office_store et potential_office_store
        this.actualOfficeStoreDiv.innerHTML = '';
        this.potentialOfficeStoreDiv.innerHTML = '';

        if (this.actualOfficesIdArray.length == 0) {
            this.actualOfficeStoreDiv.insertAdjacentHTML("beforeend", '<p>Aucun cabinet médical assigné</p>');
        } else {
            this.cardsDrawer('actual', this.actualOfficesIdArray);
        }

        if (this.potentialOfficesIdArray.length == 0) {
            this.potentialOfficeStoreDiv.insertAdjacentHTML("beforeend", '<p>Aucun cabinet médical supplémentaire disponible</p>');
        } else {
            this.cardsDrawer('potential', this.potentialOfficesIdArray);
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
    private cardsDrawer(type: string, officesIdArray: string[]): void {
        Object.keys(this.officeCardStoreObj).forEach( (longObjKey: string) => {
            const shortObjKey = longObjKey.replace('_office', '');

            if (officesIdArray.includes(shortObjKey)) {
                if (type == 'actual') {
                    this.actualOfficeStoreDiv.insertAdjacentHTML("beforeend", this.officeCardStoreObj[longObjKey]);
                    const cardElement = document.getElementById(longObjKey) as HTMLSpanElement;
                    cardElement.style.cursor = 'pointer';
                    cardElement.addEventListener("click", this.actualCardClick.bind(this));
                } else {
                    this.potentialOfficeStoreDiv.insertAdjacentHTML("beforeend", this.officeCardStoreObj[longObjKey]);
                    const cardElement = document.getElementById(longObjKey) as HTMLSpanElement;
                    if (this.actualOfficesIdArray.length < 5) {
                        cardElement.style.cursor = 'pointer';
                        cardElement.addEventListener("click", this.potentialCardClick.bind(this));
                    }
                }
            }
        });
    }

    /** Si le user clic sur une card assignée, son ID est supprimé de actualOfficesIdArray et on relance l'affichage des cards
     * @param evt
     */
    private actualCardClick(evt: MouseEvent): void {
        const clickedCard = evt.currentTarget as HTMLElement;
        clickedCard.removeEventListener("click", this.actualCardClick);
        const cardId = clickedCard.id.replace('_office', '');
        _.pull(this.actualOfficesIdArray, cardId);
        this.officeCardsMngmt();
    }

    /** Si le user clic sur une card potentielle, son ID est ajoutée à actualOfficesIdArray et on relance l'affichage des cards
     * @param evt
     */
    private potentialCardClick(evt: MouseEvent): void {
        const clickedCard = evt.currentTarget as HTMLElement;
        clickedCard.removeEventListener("click", this.potentialCardClick);
        const cardId = clickedCard.id.replace('_office', '');
        this.actualOfficesIdArray.push(cardId);
        this.officeCardsMngmt();
    }

    /** Récupération des données puis mise en forme avant envoyé en POST via AJAX au clic sur le bouton Envoyer
     */
    private formSubmit(): void {
        // récupération des ID des spé medics confirmées puis transformation
        const confirmedIdsArrayPrep: string[] = [];
        this.actualSpeMedicOfDocArray.forEach( (id, index) => {
            const template = 'speMedicID_' + index + '=' + id;
            confirmedIdsArrayPrep.push(template);
        });

        // récupératin des ID des doc offices confirmés puis transformation
        const confirmedDocOfficeIdsArrayPrep: string[] = [];
        this.actualOfficesIdArray.forEach( (id, index) => {
            const template = 'docOfficeID_' + index + '=' + id;
            confirmedDocOfficeIdsArrayPrep.push(template);
        });

        const concatenadedArrays = [
            ...confirmedIdsArrayPrep,
            ...confirmedDocOfficeIdsArrayPrep
        ];
        const params = concatenadedArrays.join('&');

        (async () => {
            let ajax = await this.allInOne.send(params);

            //const debug = document.getElementById('debug') as HTMLDivElement;
            //debug.innerHTML = ajax.response;

            if (ajax.status == 200) {
                window.location.search = '?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=' + this.docID;
            }
        })();
    }

    /** Renvoi vers la page du doc au clic sur "Annuler"
     */
    private formCancel(): void {
        window.location.search = '?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=' + this.docID;
    }
}
