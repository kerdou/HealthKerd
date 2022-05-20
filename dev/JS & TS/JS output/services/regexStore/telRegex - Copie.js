var TelRegex = /** @class */ (function () {
    function TelRegex() {
    }
    TelRegex.prototype.telRegex = function (telValue) {
        /** ^                               Doit être placé au début du numéro de tel
         *   (0|\\+33|0033)[1-9]    Doit comment par 0 ou +33 ou 0033 et être suivi d'un chiffre allant de 1 à 9. On double les \ pour que la conversion en Regex se passe bien. */
        var telBeginning = '^(0|\\+33|0033)[1-9]';
        /** ([-. ]?[0-9]{2}){4}$
         *                      $   Doit être placé à la fin de la phrase
         *                  {4}     Doit être fait 4 fois
         *  ([-. ]?[0-9]{2})        Doit commencer par - ou . ou un espace et être suivi de 2 chiffres allant chacun de 0 à 9 */
        var telEnd = '([-. ]?[0-9]{2}){4}$';
        var telConcat = telBeginning + telEnd; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
        var telRegex = new RegExp(telConcat); // création du regex
        telValue = telValue.trim();
        return telRegex.test(telValue);
    };
    return TelRegex;
}());
export default TelRegex;
