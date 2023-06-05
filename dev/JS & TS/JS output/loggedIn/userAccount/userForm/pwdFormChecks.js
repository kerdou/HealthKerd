import pwdRegex from '../../../services/regexStore/pwdRegex.js';
import _ from 'lodash';
/** Vérification d'un champ de mot de passe:
 * Modifie
 * @param {string} field - Nom du champ en cours de vérification
 * @returns {emptyPwdChecksInter} - Renvoie l'état du test
 */
export function pwdCheck(field) {
    var fieldInput = document.getElementById(field);
    var fieldValue = fieldInput.value;
    fieldValue = fieldValue.trim();
    var results = {
        pwdSummary: {
            length: 0,
            lower: 0,
            upper: 0,
            nbr: 0,
            spe: 0
        },
        rulesCheck: {
            length: false,
            lower: false,
            upper: false,
            nbr: false,
            spe: false,
            isValid: false
        }
    };
    if (fieldValue.length != 0) {
        var pwdSummary = pwdRegex(fieldValue);
        var rulesCheck = rulesChecker(pwdSummary);
        results.pwdSummary = pwdSummary;
        results.rulesCheck = rulesCheck;
        if (rulesCheck.isValid) {
            fieldInput.classList.remove('is-invalid');
        }
        else {
            fieldInput.classList.add('is-invalid');
        }
    }
    return results;
}
/** Traduit les données de chaque champ en une série de booléens selon la conformité
 * @param {pwdSummaryInter} pwdSummary - Récap des caractéres du pwd
 * @returns {rulesChecksInter} - Série de booléens
 */
export function rulesChecker(pwdSummary) {
    var rulesChecks = {
        length: false,
        lower: false,
        upper: false,
        nbr: false,
        spe: false,
        isValid: false
    };
    rulesChecks.length = pwdSummary.length >= 8 ? true : false;
    rulesChecks.lower = pwdSummary.lower >= 1 ? true : false;
    rulesChecks.upper = pwdSummary.upper >= 1 ? true : false;
    rulesChecks.nbr = pwdSummary.nbr >= 1 ? true : false;
    rulesChecks.spe = pwdSummary.spe >= 1 ? true : false;
    rulesChecks.isValid = _.includes(rulesChecks, false) ? false : true;
    return rulesChecks;
}
/** Vérification de la similarité et validité entre les 2 champs de mdp
 * @param {completePwdChecksInter} pwdCheck -     Données du 1er champ
 * @param {completePwdChecksInter} confPwdCheck - Données du 2éme champ
 * @returns {boolean} - Renvoi TRUE si le form n'est pas valide
 */
export function samePwdCheck(pwdCheck, confPwdCheck) {
    var results = {
        bothAreValid: false,
        areIdentical: false
    };
    // lancement des comparaisons des 2 champs uniquement si aucun n'est vide
    if ((Object.entries(pwdCheck.rulesCheck).length != 0) &&
        (Object.entries(confPwdCheck.rulesCheck).length != 0)) {
        var pwdInput = document.getElementById('pwd');
        var confPwdInput = document.getElementById('confPwd');
        var samePwdInput = document.getElementById('samePwd');
        if ((pwdInput.value) == (confPwdInput.value)) {
            results.areIdentical = true;
            samePwdInput.classList.remove('is-invalid');
        }
        else {
            results.areIdentical = false;
            samePwdInput.classList.add('is-invalid');
        }
    }
    // vérification de la validité du premier champ, seulement s'il n'est pas vide
    var pwdStatusIsOk = false;
    if (Object.entries(pwdCheck.rulesCheck).length != 0) {
        pwdStatusIsOk = pwdCheck.rulesCheck.isValid;
    }
    // vérification de la validité du deuxieme champ, seulement s'il n'est pas vide
    var confPwdStatusIsOk = false;
    if (Object.entries(confPwdCheck.rulesCheck).length != 0) {
        confPwdStatusIsOk = confPwdCheck.rulesCheck.isValid;
    }
    // vérification de la validité des 2 champs
    if ((pwdStatusIsOk == true) && (confPwdStatusIsOk == true)) {
        results.bothAreValid = true;
    }
    var formHasIssues = _.includes(results, false); // renvoie TRUE s'il y a un souci
    return formHasIssues;
}
