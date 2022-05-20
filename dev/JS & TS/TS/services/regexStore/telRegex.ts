export default class TelRegex
{
    public telRegex(telValue: string): boolean {
        /** ^                       Doit être placé au début du numéro de tel
         *   (([. ]?[0-9]{2}){5}    Des séries de 2 chiffres séparées par des points ou sans séparation*/
        let tel = '^([. ]?[0-9]{2}){5}';

        let telConcat = tel; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
        let telRegex = new RegExp(telConcat); // création du regex

        telValue = telValue.trim();
        return telRegex.test(telValue);
    }
}