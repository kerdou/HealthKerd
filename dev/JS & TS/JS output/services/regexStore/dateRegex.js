/** Vérification des dates au format français
 * * Format: jj/mm/aaaa
 * * Les jours et les mois peuvent être à 1 ou 2 chiffres
 * * L'année est forcément à 4 chiffres
 * @param {string} $stringToCheck     Date à vérifier
 * @return {bool}                     Résultat du test du regex
 */
export default function frenchDateRegex(dateValue) {
    var dateExpr = /^\d{1,2}\/\d{1,2}\/\d{4}$/; // Remplace par \ par des \\. Etape nécessaire avant de transformer la string en expression régulière.
    var dateRegex = new RegExp(dateExpr); // création du regex
    dateValue = dateValue.trim();
    return dateRegex.test(dateValue);
}
