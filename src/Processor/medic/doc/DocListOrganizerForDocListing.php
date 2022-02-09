<?php

namespace HealthKerd\Processor\medic\doc;

/** Classe de modification des données des docteurs pour l'affichage de la liste des docteurs */
class DocListOrganizerForDocListing extends DocListFunctionsPool
{
    protected array $docArray = array();
    protected array $speMedicList = array();

    public function __destruct()
    {
    }

    /** Modification des données des docteurs
     * * Modification du format du titre
     * * Création de la phrase mélant le titre et le nom
     * * Ajout des spécialités médicales pour chaque docteur
     * @param array $docArray               Données des docteurs consultés
     * @param array $speMedicList           Liste des spécialités médicales liées aux events
     * @return array                        Renvoie les données modifiées
     */
    public function docListOrganizer(array $docArray, array $speMedicList)
    {
        $this->docArray['uniqueDocList'] = array();
        $this->speMedicList = $speMedicList;

        foreach ($docArray as $key => $value) {
            $tempArray = array();

            $tempArray['docID'] = $value['docID'];
            $tempArray['title'] = $value['title'];
            $tempArray['firstName'] = $value['firstName'];
            $tempArray['lastName'] = $value['lastName'];
            $tempArray['fullNameSentence'] = '';
            $tempArray['speMedicList'] = array();

            array_push($this->docArray['uniqueDocList'], $tempArray);
        }

        $this->docTitleConversion();
        $this->docFullNameSentenceCreator();
        $this->docSpeMedicAdder();

        //echo '<pre>';
        //    print_r($this->docArray['uniqueDocList']);
        //    print_r($this->speMedicList);
        //echo '</pre>';

        return $this->docArray['uniqueDocList'];
    }
}
