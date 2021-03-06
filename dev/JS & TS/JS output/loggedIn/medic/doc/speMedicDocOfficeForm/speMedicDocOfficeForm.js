var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
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
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
import allInOneAJAX from './allInOneAJAX.js';
import _ from 'lodash';
var SpeMedicDocOfficeForm = /** @class */ (function () {
    function SpeMedicDocOfficeForm() {
        var _this = this;
        this.allInOne = new allInOneAJAX;
        this.badgeStoreDiv = document.getElementById("badge_store"); // DIV contenant les badges de sp??cialit??s m??dicales
        this.selectElement = document.getElementById("select"); // SELECT de choix de sp?? m??dicales
        this.addButton = document.getElementById('add_button'); // bouton ?? droite du SELECT
        this.actualOfficeStoreDiv = document.getElementById("actual_office_store");
        this.potentialOfficeStoreDiv = document.getElementById("potential_office_store");
        this.submitButton = document.getElementById("submit_button");
        this.cancelButton = document.getElementById("cancel_button");
        this.officeCardStoreObj = {}; // contient toutes les doc offices cards pr??assembl??es
        this.allInOneData = []; // toutes les donn??es r??cup??r??es en AJAX
        this.docID = ''; // ID du doc concern??
        this.everySpeMedicForDoc = []; // toutes les sp?? m??dicales attribuales au doc
        this.everyDocOfficesOfUser = []; // toutes les donn??es de tous les doc offices du user
        this.everySpeMedicOfAllDocOfficesOfUser = []; // toutes les sp?? m??dic de tous les doc offices du user
        // suivi des ID de sp??cialit??s m??dicales et de doc offices
        this.actualSpeMedicOfDocArray = []; // id de sp?? m??dic assign??es au doc
        this.actualOfficesIdArray = []; // id des doc offices assign??s au doc
        this.potentialOfficesIdArray = []; // id de tous les doc offices potentiellement assignables au doc
        // templates d'??lements HTML
        this.removableSpeMedicBadgeTemplate = ''; // template de tous les badges de sp?? medic assign??s au doc
        this.officeCardTemplate = ''; // template de doc office card
        this.speMedicBadgeForOfficeCardTemplate = ''; // template des badge de sp?? medic destin??es au doc office cards
        this.addButton.addEventListener('click', this.addSpeMedic.bind(this));
        this.submitButton.addEventListener('click', this.formSubmit.bind(this));
        this.cancelButton.addEventListener('click', this.formCancel.bind(this));
        (function () { return __awaiter(_this, void 0, void 0, function () {
            var _a;
            return __generator(this, function (_b) {
                switch (_b.label) {
                    case 0:
                        _a = this;
                        return [4 /*yield*/, this.allInOne.receive()];
                    case 1:
                        _a.allInOneData = _b.sent(); // r??cup??ration de toutes les donn??es en AJAX
                        //console.log(this.allInOneData.response);
                        //let debug = document.getElementById('debug') as HTMLDivElement;
                        //debug.innerHTML = this.allInOneData.response;
                        this.docID = this.allInOneData.response.docID;
                        this.everySpeMedicForDoc = this.allInOneData.response.everySpeMedicForDoc.pdoResult;
                        this.everyDocOfficesOfUser = this.allInOneData.response.everyDocOfficesOfUser.pdoResult;
                        this.everySpeMedicOfAllDocOfficesOfUser = this.allInOneData.response.everySpeMedicOfAllDocOfficesOfUser.pdoResult;
                        this.actualSpeMedicOfDocArray = this.initialSpeMedicIdExtractor(this.allInOneData.response.speMedicOfDoc.pdoResult);
                        this.actualOfficesIdArray = this.initialDocOfficeIdExtractor(this.allInOneData.response.docOfficesOfDoc.pdoResult);
                        // r??cup??ration des templates HTML
                        this.removableSpeMedicBadgeTemplate = this.allInOneData.response.removableSpeMedicBadgeTemplate;
                        this.officeCardTemplate = this.allInOneData.response.officeCardTemplate;
                        this.speMedicBadgeForOfficeCardTemplate = this.allInOneData.response.speMedicBadgeForOfficeCardTemplate;
                        this.docOfficeCardStoreBuilder();
                        this.speMedicBadgeBuilder();
                        return [2 /*return*/];
                }
            });
        }); })();
    }
    /** Extraction des ID de spe medic d??j?? assign??es au doc
     * @param speList
     * @returns
     */
    SpeMedicDocOfficeForm.prototype.initialSpeMedicIdExtractor = function (speList) {
        var result = [];
        speList.forEach(function (value, index) {
            result.push(value.speMedicID);
        });
        return result;
    };
    /** Extraction des ID de doc offices d??j?? assign??s au doc
     * @param officeList
     * @returns
     */
    SpeMedicDocOfficeForm.prototype.initialDocOfficeIdExtractor = function (officeList) {
        var result = [];
        officeList.forEach(function (value, index) {
            result.push(value.docOfficeID);
        });
        return result;
    };
    /** Cr??ation de tous les carss de doc office pour les stocker dans officeCardStoreObj
     */
    SpeMedicDocOfficeForm.prototype.docOfficeCardStoreBuilder = function () {
        var _this = this;
        this.everyDocOfficesOfUser.forEach(function (value, index) {
            var badgesHTML = _this.speMedicBadgeForOfficeCardBuilder(value.docOfficeID);
            var tempCard = _this.officeCardTemplate;
            tempCard = tempCard.replace('{docOfficeID}', value.docOfficeID);
            tempCard = tempCard.replace('{name}', value.name);
            tempCard = tempCard.replace('{cityName}', value.cityName);
            tempCard = tempCard.replace('{badgesHTML}', badgesHTML);
            var key = value.docOfficeID + "_office";
            _this.officeCardStoreObj[key] = tempCard; // ajout d'un caract??re ?? la fin de la cl?? pour qu'elle soit une string et qu'elle garde le bon ordre
        });
    };
    /** Construction des badges de spe medic ?? destination des cards de doc office
     * @param docOfficeID
     * @returns
     */
    SpeMedicDocOfficeForm.prototype.speMedicBadgeForOfficeCardBuilder = function (docOfficeID) {
        var _this = this;
        var badgesHTML = '';
        this.everySpeMedicOfAllDocOfficesOfUser.forEach(function (value, index) {
            if (value.docOfficeID == docOfficeID) {
                var tempBadge = _this.speMedicBadgeForOfficeCardTemplate;
                tempBadge = tempBadge.replace('{speName}', value.name);
                badgesHTML = badgesHTML.concat(tempBadge);
            }
        });
        return badgesHTML;
    };
    /** Cycle de cr??ation/recyclage des badges de sp?? medic et d'Options du Select
     * * Vidage puis remplissage de la liste de badges de sp?? m??dic
     * * Vidage puis remplissage du SELECT
     * * Lancement de la gestion des cards de doc office
     */
    SpeMedicDocOfficeForm.prototype.speMedicBadgeBuilder = function () {
        var _this = this;
        // vidage puis remplissage de la liste de badges de sp?? m??dic
        this.badgeStoreDiv.innerHTML = '';
        if (this.actualSpeMedicOfDocArray.length == 0) {
            this.badgeStoreDiv.insertAdjacentHTML("beforeend", '<p>Pas de sp??cialit?? m??dicale s??lectionn??e</p>');
        }
        else {
            this.everySpeMedicForDoc.forEach(function (everySpe, index) {
                if (_this.actualSpeMedicOfDocArray.includes(everySpe.speMedicID)) {
                    var tempBadge = _this.removableSpeMedicBadgeTemplate;
                    tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID); // utiliser replaceAll() obligerait ?? passer en lib ES2021
                    tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID); // utiliser replaceAll() obligerait ?? passer en lib ES2021
                    tempBadge = tempBadge.replace('{speName}', everySpe.nameForDoc);
                    _this.badgeStoreDiv.insertAdjacentHTML("beforeend", tempBadge);
                    var badge = document.getElementById(everySpe.speMedicID + '_spe');
                    badge.style.cursor = 'pointer';
                    badge.addEventListener("click", _this.speBadgeRemover.bind(_this));
                }
            });
        }
        // vidage puis remplissage du SELECT
        this.selectElement.innerHTML = '';
        this.everySpeMedicForDoc.forEach(function (spe, index) {
            if (_this.actualSpeMedicOfDocArray.includes(spe.speMedicID) == false) {
                var optionElement = document.createElement("option");
                optionElement.value = spe.speMedicID;
                optionElement.text = spe.nameForDoc;
                _this.selectElement.add(optionElement);
            }
        });
        this.buttonsAbilityCheck();
        this.officeCardsMngmt();
    };
    /** Suppression d'un badge de sp?? m??dicales de doc quand on a clic dessus
     * @param {MouseEvent} evt - Event du clic de souris sur le badge de sp?? medic du doc
     */
    SpeMedicDocOfficeForm.prototype.speBadgeRemover = function (evt) {
        var clickedBadge = evt.currentTarget;
        var badge = document.getElementById(clickedBadge.id);
        badge.removeEventListener("click", this.speBadgeRemover);
        var badgeId = badge.id.replace('_spe', '');
        _.pull(this.actualSpeMedicOfDocArray, badgeId);
        this.speMedicBadgeBuilder();
    };
    /** Ajout de l'ID de la sp?? medic ?? ajouter au actualSpeMedicOfDocArray puis recr??ation des badges et des Options du Select
     */
    SpeMedicDocOfficeForm.prototype.addSpeMedic = function () {
        this.actualSpeMedicOfDocArray.push(this.selectElement.value);
        this.speMedicBadgeBuilder();
    };
    /** V??rification du nombre de spe medic badges, arriv?? ?? 5 on d??sactive le bouton d'ajout de spe medic
     */
    SpeMedicDocOfficeForm.prototype.buttonsAbilityCheck = function () {
        if (this.actualSpeMedicOfDocArray.length < 5) {
            this.addButton.disabled = false;
        }
        else {
            this.addButton.disabled = true;
        }
        if (this.actualSpeMedicOfDocArray.length == 0) {
            this.submitButton.disabled = true;
        }
        else {
            this.submitButton.disabled = false;
        }
    };
    /** Gestion de la gestion des arrays d??di??s aux cards de doc office et ?? leur effacement dans le HTML avant de les redessiner
     */
    SpeMedicDocOfficeForm.prototype.officeCardsMngmt = function () {
        var _this = this;
        this.potentialOfficesIdArray = []; // vidage de potentialOfficesIdArray pour commencer un nouveau cycle
        // ajout de tous les doc offices potentiels dans potentialOfficesIdArray
        this.everySpeMedicOfAllDocOfficesOfUser.forEach(function (value, index) {
            if (_this.actualSpeMedicOfDocArray.includes(value.speMedicID)) {
                _this.potentialOfficesIdArray.push(value.docOfficeID);
            }
        });
        // suppression des offices d??j?? assign??s au doc de la liste des offices potentiels
        this.actualOfficesIdArray.forEach(function (docOfficeID, index) {
            if (_this.potentialOfficesIdArray.includes(docOfficeID)) {
                _.pull(_this.potentialOfficesIdArray, docOfficeID);
            }
        });
        // suppression des cards dans actual_office_store et potential_office_store
        this.actualOfficeStoreDiv.innerHTML = '';
        this.potentialOfficeStoreDiv.innerHTML = '';
        if (this.actualOfficesIdArray.length == 0) {
            this.actualOfficeStoreDiv.insertAdjacentHTML("beforeend", '<p>Aucun cabinet m??dical assign??</p>');
        }
        else {
            this.cardsDrawer('actual', this.actualOfficesIdArray);
        }
        if (this.potentialOfficesIdArray.length == 0) {
            this.potentialOfficeStoreDiv.insertAdjacentHTML("beforeend", '<p>Aucun cabinet m??dical suppl??mentaire disponible</p>');
        }
        else {
            this.cardsDrawer('potential', this.potentialOfficesIdArray);
        }
    };
    /** Affichage des cards dans actual_office_store et potential_office_store
     * * On fait un forEach de toutes les cards stock??es dans officeCardStoreObj
     * * On change adapte les formats d'ID des doc offices
     * * Si l'ID d'un doc office pr??sent dans officeCardStoreObj est ??galement pr??sent dans officesIdArray on lance son affichage
     * ----
     * * Cette m??thode permet de conserver l'ordre d'affichage provenant de la DB qui inclus ORDER BY doc_office_list.cityName, doc_office_list.name
     * @param type - Indique si l'on g??re un affichage dans actual_office_store ou dans potential_office_store
     * @param officesIdArray - Liste des ID des doc offices
     */
    SpeMedicDocOfficeForm.prototype.cardsDrawer = function (type, officesIdArray) {
        var _this = this;
        Object.keys(this.officeCardStoreObj).forEach(function (longObjKey) {
            var shortObjKey = longObjKey.replace('_office', '');
            if (officesIdArray.includes(shortObjKey)) {
                if (type == 'actual') {
                    _this.actualOfficeStoreDiv.insertAdjacentHTML("beforeend", _this.officeCardStoreObj[longObjKey]);
                    var cardElement = document.getElementById(longObjKey);
                    cardElement.style.cursor = 'pointer';
                    cardElement.addEventListener("click", _this.actualCardClick.bind(_this));
                }
                else {
                    _this.potentialOfficeStoreDiv.insertAdjacentHTML("beforeend", _this.officeCardStoreObj[longObjKey]);
                    var cardElement = document.getElementById(longObjKey);
                    if (_this.actualOfficesIdArray.length < 5) {
                        cardElement.style.cursor = 'pointer';
                        cardElement.addEventListener("click", _this.potentialCardClick.bind(_this));
                    }
                }
            }
        });
    };
    /** Si le user clic sur une card assign??e, son ID est supprim?? de actualOfficesIdArray et on relance l'affichage des cards
     * @param evt
     */
    SpeMedicDocOfficeForm.prototype.actualCardClick = function (evt) {
        var clickedCard = evt.currentTarget;
        clickedCard.removeEventListener("click", this.actualCardClick);
        var cardId = clickedCard.id.replace('_office', '');
        _.pull(this.actualOfficesIdArray, cardId);
        this.officeCardsMngmt();
    };
    /** Si le user clic sur une card potentielle, son ID est ajout??e ?? actualOfficesIdArray et on relance l'affichage des cards
     * @param evt
     */
    SpeMedicDocOfficeForm.prototype.potentialCardClick = function (evt) {
        var clickedCard = evt.currentTarget;
        clickedCard.removeEventListener("click", this.potentialCardClick);
        var cardId = clickedCard.id.replace('_office', '');
        this.actualOfficesIdArray.push(cardId);
        this.officeCardsMngmt();
    };
    /** R??cup??ration des donn??es puis mise en forme avant envoy?? en POST via AJAX au clic sur le bouton Envoyer
     */
    SpeMedicDocOfficeForm.prototype.formSubmit = function () {
        var _this = this;
        // r??cup??ration des ID des sp?? medics confirm??es puis transformation
        var confirmedIdsArrayPrep = [];
        this.actualSpeMedicOfDocArray.forEach(function (id, index) {
            var template = 'speMedicID_' + index + '=' + id;
            confirmedIdsArrayPrep.push(template);
        });
        // r??cup??ratin des ID des doc offices confirm??s puis transformation
        var confirmedDocOfficeIdsArrayPrep = [];
        this.actualOfficesIdArray.forEach(function (id, index) {
            var template = 'docOfficeID_' + index + '=' + id;
            confirmedDocOfficeIdsArrayPrep.push(template);
        });
        var concatenadedArrays = __spreadArray(__spreadArray([], __read(confirmedIdsArrayPrep), false), __read(confirmedDocOfficeIdsArrayPrep), false);
        var params = concatenadedArrays.join('&');
        (function () { return __awaiter(_this, void 0, void 0, function () {
            var ajax;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.allInOne.send(params)];
                    case 1:
                        ajax = _a.sent();
                        //const debug = document.getElementById('debug') as HTMLDivElement;
                        //debug.innerHTML = ajax.response;
                        if (ajax.status == 200) {
                            window.location.search = '?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=' + this.docID;
                        }
                        return [2 /*return*/];
                }
            });
        }); })();
    };
    /** Renvoi vers la page du doc au clic sur "Annuler"
     */
    SpeMedicDocOfficeForm.prototype.formCancel = function () {
        window.location.search = '?controller=medic&subCtrlr=doc&action=dispOneDoc&docID=' + this.docID;
    };
    return SpeMedicDocOfficeForm;
}());
export default SpeMedicDocOfficeForm;
