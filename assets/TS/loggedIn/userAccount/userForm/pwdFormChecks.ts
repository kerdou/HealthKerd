import PwdRegex from '../../../services/regexStore/pwdRegex.js';
import _ from 'lodash';


interface pwdSummaryInter {
    [name: string]: number;
}

interface rulesChecksInter {
    [name: string]: boolean;
}

interface pwdChecksInter {
    pwdSummary: {};
    ruleCheck: {};
}


export default class PwdFormChecks
{
    /** Vérification du nom de famille:
     * * Lancement du regex 'nameRegex'
     * * Si le test n'est pas bon, le champ passe en rouge
     * @returns {boolean} - Renvoie l'état du test
     */
    protected pwdCheck(field: string): pwdChecksInter {
        const fieldInput = document.getElementById(field) as HTMLInputElement;
        let fieldValue: string = fieldInput.value;
        fieldValue = fieldValue.trim();

        let fieldValidity = false;

        let results = {
            pwdSummary: {},
            ruleCheck: {}
        };

        if (fieldValue.length == 0) {
            fieldValidity = false;
        } else {
            const fieldTestObj = new PwdRegex;
            let pwdSummary = fieldTestObj.pwdRegex(fieldValue);
            let ruleCheck = this.rulesChecker(pwdSummary);

            results.pwdSummary = pwdSummary;
            results.ruleCheck = ruleCheck;

            if (ruleCheck.isValid) {
                fieldValidity = true;
                fieldInput.classList.remove('is-invalid');
            } else {
                fieldValidity = false;
                fieldInput.classList.add('is-invalid');
            }
        }

        return results;
    }

    /**
     *
     * @param pwdSummary
     * @returns
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


    public samePwdCheck(pwdCheck: any, confPwdCheck: any): boolean
    {
        let results = {
            bothAreValid: false,
            areIdentical: false
        }

        if (
            (Object.entries(pwdCheck.ruleCheck).length != 0) &&
            (Object.entries(confPwdCheck.ruleCheck).length != 0)
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

        let pwdStatusIsOk = false;
        let confPwdStatusIsOk = false;

        if (Object.entries(pwdCheck.ruleCheck).length != 0) {
            pwdStatusIsOk = pwdCheck.ruleCheck.isValid;
        }

        if (Object.entries(confPwdCheck.ruleCheck).length != 0) {
            confPwdStatusIsOk = confPwdCheck.ruleCheck.isValid;
        }

        if ((pwdStatusIsOk == true) && (confPwdStatusIsOk == true)) {
            results.bothAreValid = true;
        }

        let formHasIssues = _.includes(results, false); // renvoie TRUE s'il y a un souci

        return formHasIssues;
    }
}
