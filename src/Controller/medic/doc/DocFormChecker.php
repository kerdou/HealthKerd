<?php

namespace HealthKerd\Controller\medic\doc;

/** Contrôles fait sur les données entrées dans les champs des formulaires des docteurs
 */
class DocFormChecker
{
    /** Méthodes de contrôles des données envoyées aux formulaires des docteurs
     * @param array $cleanedUpPost      Liste des données entrantes
     * @return array                    Statut de conformité des entrées
     */
    public function docFormChecks(array $cleanedUpPost)
    {
        $checksArray = array();

        $lastname = html_entity_decode($cleanedUpPost['lastname']);
        $firstname = html_entity_decode($cleanedUpPost['firstname']);
        $tel = html_entity_decode($cleanedUpPost['tel']);
        $mail = html_entity_decode($cleanedUpPost['mail']);
        $webpage = html_entity_decode($cleanedUpPost['webpage']);
        $doctolibpage = html_entity_decode($cleanedUpPost['doctolibpage']);



        //$pregListForName = "/^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$/i";
        $nameBeginning = "^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+";
        // ^                                                                        Doit être situé au début de la phrase
        //  (                                                                )+     Doit inclure au moins 1 des caractères situés dans la liste suivante
        //   [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                 )*       Doit commencer par au moins 1 des caractères de la liste, la suite n'est pas obligatoire
        //                                ( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+         Doit commencer par un espace ou un ' et suivi d'au moins 1 caractère de la liste

        $nameEnd = "([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$";
        //                                                                          $   Doit être situé à la fin de la phrase
        // (                                                                      )*    Doit comporter 0 ou plusieurs éléments de la liste suivante
        //  [-](                                                                )+      Doit comporter au moins 1 tiret suivi de la liste suivante
        //      [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                 )*        Doit comporter au moins 1 des caractères listés suivi de 0 ou plusieurs caractéres de la liste suivante
        //                                   ( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+          Doit commencer par un espace ou un ' suivi d'au moins 1 caractère de la liste suivante
        $pregListForName = "/" . $nameBeginning . $nameEnd . "/i";


        $checksArray['lastname'] = (preg_match($pregListForName, $lastname) ? true : false); // test de conformité du nom de famille, s'il est bon $lastnameChecks devient true

        if (strlen($firstname) > 0) {
            $checksArray['firstname'] = (preg_match($pregListForName, $firstname) ? true : false); // test de conformité du prénom de famille, s'il est bon $firstnameChecks devient true
        } else {
            $checksArray['firstname'] = true;
        }




        // REGEX TEL
        /** ^                               Doit être placé au début du numéro de tel
         *   (0|\\+33|0033)[1-9]    Doit comment par 0 ou +33 ou 0033 et être suivi d'un chiffre allant de 1 à 9. On double les \ pour que la conversion en Regex se passe bien. */
        $telBeginning = "^(0|\+33|0033)[1-9]";

        /** ([-. ]?[0-9]{2}){4}$
         *                      $   Doit être placé à la fin de la phrase
         *                  {4}     Doit être fait 4 fois
         *  ([-. ]?[0-9]{2})        Doit commencer par - ou . ou un espace et être suivi de 2 chiffres allant chacun de 0 à 9 */
        $telEnd = "([-. ]?[0-9]{2}){4}$";
        $pregListForTel = "/" . $telBeginning . $telEnd . "/i";

        if (strlen($tel) > 0) {
            $checksArray['tel'] = (preg_match($pregListForTel, $tel) ? true : false);
        } else {
            $checksArray['tel'] = true;
        }




        // REGEX MAIL
        /** ^               Doit être placé au début de l'adresse mail
         *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste  */
        $mailBeginning = "^[a-z0-9._\-]+";

        /** @               Doit être placé après l'arobase
         *   [a-z0-9._-]+   Doit contenir au moins 1 des caractères de la liste */
        $mailMiddle = "@[a-z0-9._\-]+";

        /** \.         $   Doit être placé entre un . et la fin de l'adresse.
         *     [a-z]{2,6}   Doit contenir entre 2 et 6 lettres présents dans la liste */
        $mailEnding = "\.[a-z]{2,6}$";
        $pregListForMail = "/" . $mailBeginning . $mailMiddle . $mailEnding . "/i";

        if (strlen($mail)) {
            $checksArray['mail'] = (preg_match($pregListForMail, $mail) ? true : false);
        } else {
            $checksArray['mail'] = true;
        }


        // URL
        $regexForURL = "((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9.\-]+|(?:www.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9.\-]+)((?:\/[\+~%\/.\w\-_]*)?\??(?:[\-\+=&;%@.\w_]*)#? (?:[\w]*))?)";
        $pregListForURL = "/" . $regexForURL . "/i";

        if (strlen($webpage) > 0) {
            $checksArray['webpage'] = (preg_match($pregListForURL, $webpage) ? true : false);
        } else {
            $checksArray['webpage'] = true;
        }

        if (strlen($doctolibpage) > 0) {
            $checksArray['doctolibpage'] = (preg_match($pregListForURL, $doctolibpage) ? true : false);
        } else {
            $checksArray['doctolibpage'] = true;
        }

        return $checksArray;
    }
}
