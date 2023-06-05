import _ from 'lodash';

interface pwdSummaryInter {
    length: number,
    lower: number,
    upper: number,
    nbr: number,
    spe: number
}


/** Vérification des mots de passe
 * @param {string} stringToCheck    Mot de passe à vérifier
 * @return {RegExpMatchArray[]}       Résultat du test du regex
 */
export default function pwdRegex(stringToCheck: string): pwdSummaryInter {
    stringToCheck = stringToCheck.trim();

    const pwdExpr = /(?<lower>([a-z]+)?)(?<upper>([A-Z]+)?)(?<nbr>([0-9]+)?)(?<spe>(\W+)?)/; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
    const regex = new RegExp(pwdExpr, 'g');
    const regexResult = [...stringToCheck.matchAll(regex)];

    const pwdSummary = {
        length: stringToCheck.length,
        lower: 0,
        upper: 0,
        nbr: 0,
        spe: 0
    };

    regexResult.forEach( (arrValue, arrIndex) => {
        _.forOwn(arrValue.groups, (objValue: string, objIndex: string) => {
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
    } );

    return pwdSummary;
}