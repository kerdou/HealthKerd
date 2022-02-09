<?php

namespace HealthKerd\Processor\medic\doc;

/** Classe de modification des données des docteurs pour l'affichage des events */
class DocListOrganizerForEvent extends DocListFunctionsPool
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
     * @param array $attendedDoc            Données des docteurs présents lors les events
     * @param array $replacedDoc            Données des docteurs remplacées lors des events
     * @param array $laboOrdoDoc            Données des docteurs ayant fait une ordonnance de prélèvement en labo médical
     * @param array $laboOrdoReplacedDoc    Données des docteurs remplacés lors de la prescription d'une ordonnance de prélèvement en labo médical
     * @param array $spe_medic_full_list    Liste des spécialités médicales liées aux events
     * @return array                        Renvoie les données modifiées
     */
    public function docListOrganizer(
        array $attendedDoc,
        array $replacedDoc,
        array $laboOrdoDoc,
        array $laboOrdoReplacedDoc,
        array $spe_medic_full_list
    ) {
        $this->docArray['mergedDocs'] = [
            ...$attendedDoc,
            ...$replacedDoc,
            ...$laboOrdoDoc,
            ...$laboOrdoReplacedDoc
        ];

        $this->speMedicList = $spe_medic_full_list;

        $this->docArray['docIdList'] = array();
        $this->docArray['uniqueDocList'] = array();

        // filtre les docteurs pour qu'ils soient uniques et rassemblés dans $this->docArray['uniqueDocList']
        foreach ($this->docArray['mergedDocs'] as $key => $value) {
            if (in_array($value['docID'], $this->docArray['docIdList']) == false) {
                array_push($this->docArray['docIdList'], $value['docID']);
                array_push($this->docArray['uniqueDocList'], $value);
            }
        }

        foreach ($this->docArray['uniqueDocList'] as $key => $value) {
            $tempArray = array();

            $tempArray['docID'] = $value['docID'];
            $tempArray['title'] = $value['title'];
            $tempArray['firstName'] = $value['firstName'];
            $tempArray['lastName'] = $value['lastName'];
            $tempArray['fullNameSentence'] = '';
            $tempArray['speMedicList'] = array();
            $tempArray['booleans']['isMyMainDoc'] = $value['isMyMainDoc'];
            $tempArray['booleans']['canVisitHome'] = $value['canVisitHome'];
            $tempArray['booleans']['isRetired'] = $value['isRetired'];
            $tempArray['booleans']['isBlacklisted'] = $value['isBlacklisted'];

            $this->docArray['uniqueDocList'][$key] = $tempArray;
        }

        $this->docTitleConversion();
        $this->docFullNameSentenceCreator();
        $this->docSpeMedicAdder();

        //echo '<pre>';
        //    print_r($replacedDoc);
        //    print_r($this->docArray['uniqueDocList']);
        //    print_r($this->speMedicList);
        //echo '</pre>';

        return $this->docArray['uniqueDocList'];
    }
}
