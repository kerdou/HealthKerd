var TelRegex = /** @class */ (function () {
    function TelRegex() {
    }
    TelRegex.prototype.telRegex = function (telValue) {
        /** ^([0]{1})                                               Commence par un 0
         *           ([1-9]{1}[. ]?)                                Suivi d'un chiffre allant de 1 à 9 suivi d'un . ou pas
         *                          ([0-9]{2}[. ]?){3}              Suivi de 3 pairs de chiffres allant de 0 à 9 suivis chacun d'un . ou pas
         *                                            ([0-9]{2})$   Se termine par 2 chiffres allant de 0 à 9
         * */
        var tel = "^([0]{1})([1-9]{1}[. ]?)([0-9]{2}[. ]?){3}([0-9]{2})$";
        var telRegex = new RegExp(tel); // création du regex
        telValue = telValue.trim();
        return telRegex.test(telValue);
    };
    return TelRegex;
}());
export default TelRegex;
