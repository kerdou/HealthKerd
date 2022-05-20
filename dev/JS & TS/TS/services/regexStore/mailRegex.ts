export default class MailRegex
{
    public mailRegex(mailValue: string): boolean {
        //    ^[a-z0-9]?(?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*[a-z0-9]+@[a-z0-9]+(?:[\.\-][a-z0-9]+)*\.[a-z]{2,8}$    i
        //    On double les \ dans les strings pour que la conversion en Regex se passe bien.

        /** Avant l'arobase
         * ^[a-z0-9]?(?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*[a-z0-9]+
         */
        /** Le 1er caractére de l'adresse doit être [a-z0-9] ou ne pas exister     */
        let addrStart = '^[a-z0-9]?';

        /** Partie optionnelle:
         *  commençant par au moins 1 [a-z0-9] possiblement suivi d'un [.!#$%&'*+-/=?^_\`{|}~]
         * (?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*     */
        let addrOptionnalMidPortion = "(?:[a-z0-9]+[\\.!#\\$%&'\\*\\+\\-\\/=\\?\\^_\`\\{\\|\\}~]?)*";

        /** L'adresse avant l'arobase doit impérativement se terminer par un caractére [a-z0-9]    */
        let addrEnd = '[a-z0-9]+';

        let completeAddr = addrStart + addrOptionnalMidPortion + addrEnd;


        /** Après l'arobase
         * @[a-z0-9]+(?:[\.\-][a-z0-9]+)*\.[a-z]{2,8}$
         */

        /** Le 1er char derriere @ doit être [a-z0-9]    */
        let domAfterArobase = '@[a-z0-9]+';

        /** Partie optionnelle:
         * Doit commencer par un . ou un - suivi d'au moins un [a-z0-9]
         * Peut être présent zéro ou plusieurs fois
         * (?:[\.\-][a-z0-9]+)*         */
        let domOptionnalMidPortion = '(?:[\\.\\-][a-z0-9]+)*';

        /** Le domaine doit se terminer avec un . suivi de 2 à 8 [a-z]
         * \.[a-z]{2,8}$  */
        let domEnding = '\\.[a-z]{2,8}$';

        let completeDom = domAfterArobase + domOptionnalMidPortion + domEnding;

        let mailConcat = completeAddr + completeDom;
        let mailRegex = new RegExp(mailConcat, 'i'); // création du regex
        mailValue = mailValue.trim();
        return mailRegex.test(mailValue);
    }
}