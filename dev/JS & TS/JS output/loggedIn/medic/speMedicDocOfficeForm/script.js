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
var allInOne = new allInOneAJAX;
var badgeStoreDiv = document.getElementById("badge_store"); // DIV contenant les badges de sp??cialit??s m??dicales
var selectElement = document.getElementById("select"); // SELECT de choix de sp?? m??dicales
var addButton = document.getElementById('add_button'); // bouton ?? droite du SELECT
addButton.addEventListener('click', addSpeMedic);
var actualOfficeStoreDiv = document.getElementById("actual_office_store");
var potentialOfficeStoreDiv = document.getElementById("potential_office_store");
var submitButton = document.getElementById("submit_button");
submitButton.addEventListener('click', formSubmit);
var officeCardStoreObj = {}; // contient toutes les doc offices cards pr??assembl??es
var allInOneData = []; // toutes les donn??es r??cup??r??es en AJAX
var everySpeMedic = []; // toutes les sp?? m??dicales
var everySpeMedicForDoc = []; // toutes les sp?? m??dicales attribuales au doc
var everyDocOfficesOfUser = []; // toutes les donn??es de tous les doc offices du user
var everySpeMedicOfAllDocOfficesOfUser = []; // toutes les sp?? m??dic de tous les doc offices du user
// suivi des ID de sp??cialit??s m??dicales et de doc offices
var actualSpeMedicOfDocArray = []; // id de sp?? m??dic assign??es au doc
var actualOfficesIdArray = []; // id des doc offices assign??s au doc
var potentialOfficesIdArray = []; // id de tous les doc offices potentiellement assignables au doc
// templates d'??lements HTML
var removableSpeMedicBadgeTemplate = ''; // template de tous les badges de sp?? medic assign??s au doc
var officeCardTemplate = ''; // template de doc office card
var speMedicBadgeForOfficeCardTemplate = ''; // template des badge de sp?? medic destin??es au doc office cards
var userID = 1;
var docID = 4; // id du docteur voulu
(function () { return __awaiter(void 0, void 0, void 0, function () {
    return __generator(this, function (_a) {
        switch (_a.label) {
            case 0: return [4 /*yield*/, allInOne.receive(userID, docID)];
            case 1:
                allInOneData = _a.sent(); // r??cup??ration de toutes les donn??es en AJAX
                console.log(allInOneData.response);
                everySpeMedic = allInOneData.response.everySpeMedic.pdoResult;
                everySpeMedicForDoc = allInOneData.response.everySpeMedicForDoc.pdoResult;
                everyDocOfficesOfUser = allInOneData.response.everyDocOfficesOfUser.pdoResult;
                everySpeMedicOfAllDocOfficesOfUser = allInOneData.response.everySpeMedicOfAllDocOfficesOfUser.pdoResult;
                actualSpeMedicOfDocArray = initialSpeMedicIdExtractor(allInOneData.response.speMedicOfDoc.pdoResult);
                actualOfficesIdArray = initialDocOfficeIdExtractor(allInOneData.response.docOfficesOfDoc.pdoResult);
                // r??cup??ration des templates HTML
                removableSpeMedicBadgeTemplate = allInOneData.response.removableSpeMedicBadgeTemplate;
                officeCardTemplate = allInOneData.response.officeCardTemplate;
                speMedicBadgeForOfficeCardTemplate = allInOneData.response.speMedicBadgeForOfficeCardTemplate;
                docOfficeCardStoreBuilder();
                speMedicBadgeBuilder();
                return [2 /*return*/];
        }
    });
}); })();
/** Extraction des ID de spe medic d??j?? assign??es au doc
 * @param speList
 * @returns
 */
function initialSpeMedicIdExtractor(speList) {
    var result = [];
    speList.forEach(function (value, index) {
        result.push(value.speMedicID);
    });
    return result;
}
/** Extraction des ID de doc offices d??j?? assign??s au doc
 * @param officeList
 * @returns
 */
function initialDocOfficeIdExtractor(officeList) {
    var result = [];
    officeList.forEach(function (value, index) {
        result.push(value.docOfficeID);
    });
    return result;
}
/** Cr??ation de tous les carss de doc office pour les stocker dans officeCardStoreObj
 */
function docOfficeCardStoreBuilder() {
    everyDocOfficesOfUser.forEach(function (value, index) {
        var badgesHTML = speMedicBadgeForOfficeCardBuilder(value.docOfficeID);
        var tempCard = officeCardTemplate;
        tempCard = tempCard.replace('{docOfficeID}', value.docOfficeID);
        tempCard = tempCard.replace('{name}', value.name);
        tempCard = tempCard.replace('{cityName}', value.cityName);
        tempCard = tempCard.replace('{badgesHTML}', badgesHTML);
        var key = value.docOfficeID + "_office";
        officeCardStoreObj[key] = tempCard; // ajout d'un caract??re ?? la fin de la cl?? pour qu'elle soit une string et qu'elle garde le bon ordre
    });
}
/** Construction des badges de spe medic ?? destination des cards de doc office
 * @param docOfficeID
 * @returns
 */
function speMedicBadgeForOfficeCardBuilder(docOfficeID) {
    var badgesHTML = '';
    everySpeMedicOfAllDocOfficesOfUser.forEach(function (value, index) {
        if (value.docOfficeID == docOfficeID) {
            var tempBadge = speMedicBadgeForOfficeCardTemplate;
            tempBadge = tempBadge.replace('{speName}', value.name);
            badgesHTML = badgesHTML.concat(tempBadge);
        }
    });
    return badgesHTML;
}
/** Cycle de cr??ation/recyclage des badges de sp?? medic et d'Options du Select
 * * Vidage puis remplissage de la liste de badges de sp?? m??dic
 * * Vidage puis remplissage du SELECT
 * * Lancement de la gestion des cards de doc office
 */
function speMedicBadgeBuilder() {
    // vidage puis remplissage de la liste de badges de sp?? m??dic
    badgeStoreDiv.innerHTML = '';
    if (actualSpeMedicOfDocArray.length == 0) {
        badgeStoreDiv.insertAdjacentHTML("beforeend", '<p>Pas de sp??cialit?? m??dicale s??lectionn??e</p>');
    }
    else {
        everySpeMedicForDoc.forEach(function (everySpe, index) {
            if (actualSpeMedicOfDocArray.includes(everySpe.speMedicID)) {
                var tempBadge = removableSpeMedicBadgeTemplate;
                tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID);
                tempBadge = tempBadge.replace('{speMedicID}', everySpe.speMedicID);
                tempBadge = tempBadge.replace('{speName}', everySpe.nameForDoc);
                badgeStoreDiv.insertAdjacentHTML("beforeend", tempBadge);
                var badge = document.getElementById(everySpe.speMedicID + '_spe');
                badge.style.cursor = 'pointer';
                badge.addEventListener("click", speBadgeRemover);
            }
        });
    }
    // vidage puis remplissage du SELECT
    selectElement.innerHTML = '';
    everySpeMedicForDoc.forEach(function (spe, index) {
        if (actualSpeMedicOfDocArray.includes(spe.speMedicID) == false) {
            var optionElement = document.createElement("option");
            optionElement.value = spe.speMedicID;
            optionElement.text = spe.nameForDoc;
            selectElement.add(optionElement);
        }
    });
    addButtonAbilityCheck();
    officeCardsMngmt();
}
/** Suppression d'un badge de sp?? m??dicales de doc quand on a clic dessus
 * @param {MouseEvent} evt - Event du clic de souris sur le badge de sp?? medic du doc
 */
function speBadgeRemover(evt) {
    var clickedBadge = evt.currentTarget;
    var badge = document.getElementById(clickedBadge.id);
    badge.removeEventListener("click", speBadgeRemover);
    var badgeId = badge.id.replace('_spe', '');
    _.pull(actualSpeMedicOfDocArray, badgeId);
    speMedicBadgeBuilder();
}
/** Ajout de l'ID de la sp?? medic ?? ajouter au actualSpeMedicOfDocArray puis recr??ation des badges et des Options du Select
 */
function addSpeMedic() {
    actualSpeMedicOfDocArray.push(selectElement.value);
    speMedicBadgeBuilder();
}
/** V??rification du nombre de spe medic badges, arriv?? ?? 5 on d??sactive le bouton d'ajout de spe medic
 */
function addButtonAbilityCheck() {
    var badgeListQty = badgeStoreDiv.children.length;
    if (badgeListQty < 5) {
        addButton.disabled = false;
    }
    else {
        addButton.disabled = true;
    }
}
/** Gestion de la gestion des arrays d??di??s aux cards de doc office et ?? leur effacement dans le HTML avant de les redessiner
 */
function officeCardsMngmt() {
    potentialOfficesIdArray = []; // vidage de potentialOfficesIdArray pour commencer un nouveau cycle
    // ajout de tous les doc offices potentiels dans potentialOfficesIdArray
    everySpeMedicOfAllDocOfficesOfUser.forEach(function (value, index) {
        if (actualSpeMedicOfDocArray.includes(value.speMedicID)) {
            potentialOfficesIdArray.push(value.docOfficeID);
        }
    });
    // suppression des offices d??j?? assign??s au doc de la liste des offices potentiels
    actualOfficesIdArray.forEach(function (docOfficeID, index) {
        if (potentialOfficesIdArray.includes(docOfficeID)) {
            _.pull(potentialOfficesIdArray, docOfficeID);
        }
    });
    // suppression des cards dans actual_office_store et potential_office_store
    actualOfficeStoreDiv.innerHTML = '';
    potentialOfficeStoreDiv.innerHTML = '';
    if (actualOfficesIdArray.length == 0) {
        actualOfficeStoreDiv.insertAdjacentHTML("beforeend", '<p>Aucun cabinet m??dical assign??</p>');
    }
    else {
        cardsDrawer('actual', actualOfficesIdArray);
    }
    if (potentialOfficesIdArray.length == 0) {
        potentialOfficeStoreDiv.insertAdjacentHTML("beforeend", '<p>Aucun cabinet m??dical suppl??mentaire disponible</p>');
    }
    else {
        cardsDrawer('potential', potentialOfficesIdArray);
    }
}
/** Affichage des cards dans actual_office_store et potential_office_store
 * * On fait un forEach de toutes les cards stock??es dans officeCardStoreObj
 * * On change adapte les formats d'ID des doc offices
 * * Si l'ID d'un doc office pr??sent dans officeCardStoreObj est ??galement pr??sent dans officesIdArray on lance son affichage
 * ----
 * * Cette m??thode permet de conserver l'ordre d'affichage provenant de la DB qui inclus ORDER BY doc_office_list.cityName, doc_office_list.name
 * @param type - Indique si l'on g??re un affichage dans actual_office_store ou dans potential_office_store
 * @param officesIdArray - Liste des ID des doc offices
 */
function cardsDrawer(type, officesIdArray) {
    Object.keys(officeCardStoreObj).forEach(function (longObjKey) {
        var shortObjKey = longObjKey.replace('_office', '');
        if (officesIdArray.includes(shortObjKey)) {
            if (type == 'actual') {
                actualOfficeStoreDiv.insertAdjacentHTML("beforeend", officeCardStoreObj[longObjKey]);
                var cardElement = document.getElementById(longObjKey);
                cardElement.style.cursor = 'pointer';
                cardElement.addEventListener("click", actualCardClick);
            }
            else {
                potentialOfficeStoreDiv.insertAdjacentHTML("beforeend", officeCardStoreObj[longObjKey]);
                var cardElement = document.getElementById(longObjKey);
                if (actualOfficesIdArray.length < 5) {
                    cardElement.style.cursor = 'pointer';
                    cardElement.addEventListener("click", potentialCardClick);
                }
            }
        }
    });
}
/** Si le user clic sur une card assign??e, son ID est supprim?? de actualOfficesIdArray et on relance l'affichage des cards
 * @param evt
 */
function actualCardClick(evt) {
    var clickedCard = evt.currentTarget;
    clickedCard.removeEventListener("click", actualCardClick);
    var cardId = clickedCard.id.replace('_office', '');
    _.pull(actualOfficesIdArray, cardId);
    officeCardsMngmt();
}
/** Si le user clic sur une card potentielle, son ID est ajout??e ?? actualOfficesIdArray et on relance l'affichage des cards
 * @param evt
 */
function potentialCardClick(evt) {
    var clickedCard = evt.currentTarget;
    clickedCard.removeEventListener("click", potentialCardClick);
    var cardId = clickedCard.id.replace('_office', '');
    actualOfficesIdArray.push(cardId);
    officeCardsMngmt();
}
/** R??cup??ration des donn??es puis mise en forme avant envoy?? en POST via AJAX
 */
function formSubmit() {
    var _this = this;
    // pr??paration du docID
    var docIdPrep = ['docID=' + docID];
    // r??cup??ration des ID des sp?? medics confirm??es
    var confirmedSpeMedicBadges = Array.from(badgeStoreDiv.children);
    var confirmedIdsArrayPrep = [];
    confirmedSpeMedicBadges.forEach(function (value, index) {
        var speId = value.id.replace('_spe', '');
        var template = 'speMedicID[' + index + ']=' + speId;
        confirmedIdsArrayPrep.push(template);
    });
    // r??cup??ratin des ID des doc offices confirm??s
    var confirmedDocOfficeCards = Array.from(actualOfficeStoreDiv.children);
    var confirmedDocOfficeIdsArrayPrep = [];
    confirmedDocOfficeCards.forEach(function (value, index) {
        var docOfficeId = value.id.replace('_office', '');
        var template = 'docOfficeID[' + index + ']=' + docOfficeId;
        confirmedDocOfficeIdsArrayPrep.push(template);
    });
    var concatenadedArrays = __spreadArray(__spreadArray(__spreadArray([], __read(docIdPrep), false), __read(confirmedIdsArrayPrep), false), __read(confirmedDocOfficeIdsArrayPrep), false);
    var params = concatenadedArrays.join('&');
    (function () { return __awaiter(_this, void 0, void 0, function () {
        var ajax, debug;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0: return [4 /*yield*/, allInOne.send(params)];
                case 1:
                    ajax = _a.sent();
                    debug = document.getElementById('debug');
                    debug.innerHTML = ajax.response;
                    if (ajax.status == 200) {
                        location.reload();
                    }
                    return [2 /*return*/];
            }
        });
    }); })();
}
