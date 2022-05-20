var TelRegex = /** @class */ (function () {
    function TelRegex() {
    }
    TelRegex.prototype.telRegex = function (telValue) {
        /** ^                       Doit être placé au début du numéro de tel
         *   (([. ]?[0-9]{2}){5}    Des séries de 2 chiffres séparées par des points ou sans séparation*/
        var tel = '^([. ]?[0-9]{2}){5}';
        var telConcat = tel; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
        var telRegex = new RegExp(telConcat); // création du regex
        telValue = telValue.trim();
        return telRegex.test(telValue);
    };
    return TelRegex;
}());
export default TelRegex;
