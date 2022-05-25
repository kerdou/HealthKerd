export default class TelRegex
{
    public telRegex(telValue: string): boolean {
      /** ^                                 Doit être placé au début du numéro de tel
       *   ^([0-9]{2}[. ]?){4}              4 séries de 2 chiffres séparées par un point ou sans séparation
       *                      ([0-9]{2})$   Suivies de 2 chiffres
       * */
       let tel = "^([0-9]{2}[. ]?){4}([0-9]{2})$";

        let telConcat = tel; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
        let telRegex = new RegExp(telConcat); // création du regex

        telValue = telValue.trim();
        return telRegex.test(telValue);
    }
}