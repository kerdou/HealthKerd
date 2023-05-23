import pwdRegex from '../../../services/regexStore/pwdRegex.js';
import _ from 'lodash';


interface pwdSummaryInter {
    [name: string]: number;
}

interface rulesChecksInter {
    [name: string]: boolean;
}

interface emptyPwdChecksInter {
    [name: string]: {
        [index: string]: number;
    };
}

interface completePwdChecksInter {
    [name: string]: {
        [name: string]: number;
    };
}



/** Vérification d'un champ de mot de passe:
 * Modifie
 * @param {string} [field] - Nom du champ en cours de vérification
 * @returns {emptyPwdChecksInter} - Renvoie l'état du test
 */
export function pwdCheck(field: string): emptyPwdChecksInter {
    const fieldInput = document.getElementById(field) as HTMLInputElement;
    let fieldValue: string = fieldInput.value;
    fieldValue = fieldValue.trim();

    const results = {
        pwdSummary: {},
        rulesCheck: {}
    };

    if (fieldValue.length != 0) {
        const pwdSummary = pwdRegex(fieldValue);
        const rulesCheck = rulesChecker(pwdSummary);

        results.pwdSummary = pwdSummary;
        results.rulesCheck = rulesCheck;

        if (rulesCheck.isValid) {
            fieldInput.classList.remove('is-invalid');
        } else {
            fieldInput.classList.add('is-invalid');
        }
    }

    return results;
}

/** Traduit les données de chaque champ en une série de booléens selon la conformité
 * @param {pwdSummaryInter} [pwdSummary] - Récap des caractéres du pwd
 * @returns {rulesChecksInter} - Série de booléens
 */
export function rulesChecker(pwdSummary: pwdSummaryInter): rulesChecksInter
{
    const rulesChecks = {
        length: false,
        lower: false,
        upper: false,
        nbr: false,
        spe: false,
        isValid: true
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
 * @param {completePwdChecksInter} [pwdCheck] -     Données du 1er champ
 * @param {completePwdChecksInter} [confPwdCheck] - Données du 2éme champ
 * @returns {boolean} - Renvoi TRUE si le form n'est pas valide
 */
export function samePwdCheck(
        pwdCheck: completePwdChecksInter,
        confPwdCheck: completePwdChecksInter
): boolean
{
    const results = {
        bothAreValid: false,
        areIdentical: false
    };

    // lancement des comparaisons des 2 champs uniquement si aucun n'est vide
    if (
        (Object.entries(pwdCheck.rulesCheck).length != 0) &&
        (Object.entries(confPwdCheck.rulesCheck).length != 0)
    ) {
        const pwdInput = document.getElementById('pwd') as HTMLInputElement;
        const confPwdInput = document.getElementById('confPwd') as HTMLInputElement;
        const samePwdInput = document.getElementById('samePwd') as HTMLInputElement;

        if ((pwdInput.value) == (confPwdInput.value)) {
            results.areIdentical = true;
            samePwdInput.classList.remove('is-invalid');
        } else {
            results.areIdentical = false;
            samePwdInput.classList.add('is-invalid');
        }
    }

    // vérification de la validité du premier champ, seulement s'il n'est pas vide
    let pwdStatusIsOk = 0;
    if (Object.entries(pwdCheck.rulesCheck).length != 0) {
        pwdStatusIsOk = pwdCheck.rulesCheck.isValid;
    }

    // vérification de la validité du deuxieme champ, seulement s'il n'est pas vide
    let confPwdStatusIsOk = 0;
    if (Object.entries(confPwdCheck.rulesCheck).length != 0) {
        confPwdStatusIsOk = confPwdCheck.rulesCheck.isValid;
    }

    // vérification de la validité des 2 champs
    if ((pwdStatusIsOk == 1) && (confPwdStatusIsOk == 1)) {
        results.bothAreValid = true;
    }

    const formHasIssues = _.includes(results, false); // renvoie TRUE s'il y a un souci

    return formHasIssues;
}