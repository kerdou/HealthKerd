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
import _ from 'lodash';
var PwdRegex = /** @class */ (function () {
    function PwdRegex() {
    }
    /** Vérification des mots de passe
     * @param {string} stringToCheck    Mot de passe à vérifier
     * @return {RegExpMatchArray[]}       Résultat du test du regex
     */
    PwdRegex.prototype.pwdRegex = function (stringToCheck) {
        stringToCheck = stringToCheck.trim();
        var pwdExpr = /(?<lower>([a-z]+)?)(?<upper>([A-Z]+)?)(?<nbr>([0-9]+)?)(?<spe>(\W+)?)/; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
        var regex = new RegExp(pwdExpr, 'g');
        var regexResult = __spreadArray([], __read(stringToCheck.matchAll(regex)), false);
        var pwdSummary = {
            length: stringToCheck.length,
            lower: 0,
            upper: 0,
            nbr: 0,
            spe: 0
        };
        regexResult.forEach(function (arrValue, arrIndex) {
            _.forOwn(arrValue.groups, function (objValue, objIndex) {
                switch (objIndex) {
                    case 'lower':
                        pwdSummary.lower += objValue.length;
                        break;
                    case 'upper':
                        pwdSummary.upper += objValue.length;
                        break;
                    case 'nbr':
                        pwdSummary.nbr += objValue.length;
                        break;
                    case 'spe':
                        pwdSummary.spe += objValue.length;
                        break;
                }
            });
        });
        return pwdSummary;
    };
    return PwdRegex;
}());
export default PwdRegex;
