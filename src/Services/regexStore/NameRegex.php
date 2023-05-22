<?php

namespace HealthKerd\Services\regexStore;

class NameRegex
{
    public function __destruct()
    {
    }

    public function nameRegex(string $stringToCheck): bool
    {
        $stringToCheck = html_entity_decode($stringToCheck);

        //$pregListForName = "/^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |'‘’)[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |'‘’)[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$/i";
        $nameBeginning = "^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |'‘’)[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+";
        // ^                                                                          Doit être situé au début de la phrase
        //  (                                                                  )+     Doit inclure au moins 1 des caractères situés dans la liste suivante
        //   [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                   )*       Doit commencer par au moins 1 des caractères de la liste, la suite n'est pas obligatoire
        //                                ( |'‘’)[a-zàáâäçèéêëìíîïñòóôöùúûü]+         Doit commencer par un espace ou un ' et suivi d'au moins 1 caractère de la liste

        $nameEnd = "([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |'‘’)[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$";
        //                                                                            $   Doit être situé à la fin de la phrase
        // (                                                                        )*    Doit comporter 0 ou plusieurs éléments de la liste suivante
        //  [-](                                                                  )+      Doit comporter au moins 1 tiret suivi de la liste suivante
        //      [a-zàáâäçèéêëìíîïñòóôöùúûü]+(                                   )*        Doit comporter au moins 1 des caractères listés suivi de 0 ou plusieurs caractéres de la liste suivante
        //                                   ( |'‘’)[a-zàáâäçèéêëìíîïñòóôöùúûü]+          Doit commencer par un espace ou un ' suivi d'au moins 1 caractère de la liste suivante

        $pregListForName = "/" . $nameBeginning . $nameEnd . "/i";
        $regexResult = (preg_match($pregListForName, $stringToCheck) ? true : false); // test de conformité du nom de famille, s'il est bon $lastnameChecks devient true

        return $regexResult;
    }
}
