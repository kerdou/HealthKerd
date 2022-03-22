export default class MailRegex
{
    public mailRegex(mailValue: string): boolean {
        /** ^               Doit être placé au début de l'adresse mail
         *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste  */
        let mailBeginning = '^[a-z0-9._-]+';

        /** @               Doit être placé après l'arobase
         *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste */
        let mailMiddle = '@[a-z0-9._-]+';

        /** \\.         $   Doit être placé entre un . et la fin de l'adresse.  On double les \ pour que la conversion en Regex se passe bien.
         *     [a-z]{2,6}   Doit contenir entre 2 et 6 lettres présents dans la liste */
        let mailEnding = '\\.[a-z]{2,6}$';

        let mailConcat = mailBeginning + mailMiddle + mailEnding;
        let mailRegex = new RegExp(mailConcat); // création du regex

        mailValue = mailValue.trim();
        return mailRegex.test(mailValue);
    }
}