import _ from 'lodash';

export default class PwdRegex
{
    /** Vérification des mots de passe
     * @param {string} stringToCheck    Mot de passe à vérifier
     * @return {RegExpMatchArray[]}       Résultat du test du regex
     */
    public pwdRegex(stringToCheck: string) {
        stringToCheck = stringToCheck.trim();

        let pwdExpr = /(?<lower>([a-z]+)?)(?<upper>([A-Z]+)?)(?<nbr>([0-9]+)?)(?<spe>(\W+)?)/; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
        let regex = new RegExp(pwdExpr, 'g');
        let regexResult = [...stringToCheck.matchAll(regex)];

        let pwdSummary = {
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
}