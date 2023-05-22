var MailRegex = /** @class */ (function () {
    function MailRegex() {
    }
    MailRegex.prototype.mailRegex = function (mailValue) {
        //    ^[a-z0-9]?(?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*[a-z0-9]+@[a-z0-9]+(?:[\.\-][a-z0-9]+)*\.[a-z]{2,8}$    i
        //    On double les \ dans les strings pour que la conversion en Regex se passe bien.
        /** Avant l'arobase
         * ^[a-z0-9]?(?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*[a-z0-9]+
         */
        /** Le 1er caractére de l'adresse doit être [a-z0-9] ou ne pas exister     */
        var addrStart = '^[a-z0-9]?';
        /** Partie optionnelle:
         *  commençant par au moins 1 [a-z0-9] possiblement suivi d'un [.!#$%&'*+-/=?^_`{|}~]
         * (?:[a-z0-9]+[\.!#\$%&'\*\+\-\/=\?\^_`\{\|\}~]?)*     */
        var addrOptionnalMidPortion = "(?:[a-z0-9]+[\\.!#\\$%&'‘’\\*\\+\\-\\/=\\?\\^_`\\{\\|\\}~]?)*";
        /** L'adresse avant l'arobase doit impérativement se terminer par un caractére [a-z0-9]    */
        var addrEnd = '[a-z0-9]+';
        var completeAddr = addrStart + addrOptionnalMidPortion + addrEnd;
        /** Après l'arobase
         * @[a-z0-9]+(?:[\.\-][a-z0-9]+)*\.[a-z]{2,8}$
         */
        /** Le 1er char derriere @ doit être [a-z0-9]    */
        var domAfterArobase = '@[a-z0-9]+';
        /** Partie optionnelle:
         * Doit commencer par un . ou un - suivi d'au moins un [a-z0-9]
         * Peut être présent zéro ou plusieurs fois
         * (?:[\.\-][a-z0-9]+)*         */
        var domOptionnalMidPortion = '(?:[\\.\\-][a-z0-9]+)*';
        /** Le domaine doit se terminer avec un . suivi de 2 à 8 [a-z]
         * \.[a-z]{2,8}$  */
        var domEnding = '\\.[a-z]{2,8}$';
        var completeDom = domAfterArobase + domOptionnalMidPortion + domEnding;
        var mailConcat = completeAddr + completeDom;
        var mailRegex = new RegExp(mailConcat, 'i'); // création du regex
        mailValue = mailValue.trim();
        return mailRegex.test(mailValue);
    };
    return MailRegex;
}());
export default MailRegex;
