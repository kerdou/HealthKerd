export default class NameRegex
{
    public nameRegex(nameValue: string): boolean {
        /** ^                                                                       Doit être placé au début de la phrase
         *   (                                                                )+    Doit contenir au moins 1 des éléments de la lite à suivre
         *    [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                 )*      Doit contenir au moins 1 des éléments de la liste et doit être suivi de 0 ou plusieurs éléments de la liste suivante
         *                                 ( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+        Doit être suivi d'un espace ou d'un ' puis suivi d'au moins 1 des éléments de la liste */
        let nameBeginning =
        "^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+";

        /**                                                                         $   Doit être placé à la fin de la phrase
         * (                                                                      )*    Peut apparaitre 0 ou plusieurs fois
         *  [-](                                                                 )+     Doit commencer par un - et être suivi d'au moins 1 élément de la liste à suivre
         *      [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                  )*       Doit contenir au moins 1 des éléments de la liste et doit être suivi de 0 ou plusieurs éléments de la liste suivante
         *                                   ( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+          Doit être suivi d'un espace ou d'un ' puis suivi d'au moins 1 des éléments de la liste */
        let nameEnding =
            "([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$";

        let nameConcat = nameBeginning + nameEnding; // Etape nécessaire avant de transformer la string en expression régulière.
        let nameModifier = 'i'; // insensible à la casse
        let nameRegex = new RegExp(nameConcat, nameModifier); // création du regex

        nameValue = nameValue.trim();
        return nameRegex.test(nameValue);
    }
}