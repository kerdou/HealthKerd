import PwdRegex from '../../../services/regexStore/pwdRegex.js';
import _ from 'lodash';


interface pwdSummaryInter {
    [name: string]: number;
}

interface rulesChecksInter {
    [name: string]: boolean;
}

interface emptyPwdChecksInter {
    pwdSummary: {};
    rulesCheck: {};
}

interface completePwdChecksInter {
    pwdSummary: {
        [name: string]: number;
    };
    rulesCheck: {
        [name: string]: boolean;
    };
}


export default class PwdFormChecks
{
    /** Vérification d'un champ de mot de passe:
     * Modifie
     * @param {string} [field] - Nom du champ en cours de vérification
     * @returns {emptyPwdChecksInter} - Renvoie l'état du test
     */
    protected pwdCheck(field: string): emptyPwdChecksInter {
        const fieldInput = document.getElementById(field) as HTMLInputElement;
        let fieldValue: string = fieldInput.value;
        fieldValue = fieldValue.trim();

        let fieldValidity = false;

        let results = {
            pwdSummary: {},
            rulesCheck: {}
        };

        if (fieldValue.length == 0) {
            fieldValidity = false;
        } else {
            const fieldTestObj = new PwdRegex;
            let pwdSummary = fieldTestObj.pwdRegex(fieldValue);
            let rulesCheck = this.rulesChecker(pwdSummary);

            results.pwdSummary = pwdSummary;
            results.rulesCheck = rulesCheck;

            if (rulesCheck.isValid) {
                fieldValidity = true;
                fieldInput.classList.remove('is-invalid');
            } else {
                fieldValidity = false;
                fieldInput.classList.add('is-invalid');
            }
        }

        return results;
    }

    /** Traduit les données de chaque champ en une série de booléens selon la conformité
     * @param {pwdSummaryInter} [pwdSummary] - Récap des caractéres du pwd
     * @returns {rulesChecksInter} - Série de booléens
     */
    private rulesChecker(pwdSummary: pwdSummaryInter): rulesChecksInter
    {
        let rulesChecks = {
            length: false,
            lower: false,
            upper: false,
            nbr: false,
            spe: false,
            isValid: true
        }

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
    public samePwdCheck(
        pwdCheck: completePwdChecksInter,
        confPwdCheck: completePwdChecksInter
    ): boolean
    {
        let results = {
            bothAreValid: false,
            areIdentical: false
        }

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
        let pwdStatusIsOk: boolean | undefined = false;
        if (Object.entries(pwdCheck.rulesCheck).length != 0) {
            pwdStatusIsOk = pwdCheck.rulesCheck.isValid;
        }

        // vérification de la validité du deuxieme champ, seulement s'il n'est pas vide
        let confPwdStatusIsOk: boolean | undefined = false;
        if (Object.entries(confPwdCheck.rulesCheck).length != 0) {
            confPwdStatusIsOk = confPwdCheck.rulesCheck.isValid;
        }

        // vérification de la validité des 2 champs
        if ((pwdStatusIsOk == true) && (confPwdStatusIsOk == true)) {
            results.bothAreValid = true;
        }

        let formHasIssues = _.includes(results, false); // renvoie TRUE s'il y a un souci

        return formHasIssues;
    }
}
