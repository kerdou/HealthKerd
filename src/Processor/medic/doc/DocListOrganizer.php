<?php

namespace HealthKerd\Processor\medic\doc;

/** Classe de modification des données des docteurs */
class DocListOrganizer
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

    /** Remplacement du titre du pro de santé pour être plus présentable, placé dans $docArray['uniqueDocList'][$key]['title']
     * * Etape indispensable avant de lancer docFullNameSentenceCreator()
     */
    private function docTitleConversion()
    {
        foreach ($this->docArray['uniqueDocList'] as $key => $value) {
            switch ($value['title']) {
                case 'dr':
                    $this->docArray['uniqueDocList'][$key]['title'] = 'Dr ';
                    break;
                case 'mr':
                    $this->docArray['uniqueDocList'][$key]['title'] = 'Mr ';
                    break;
                case 'mrs':
                    $this->docArray['uniqueDocList'][$key]['title'] = 'Mme ';
                    break;
                case 'ms':
                    $this->docArray['uniqueDocList'][$key]['title'] = 'Mlle ';
                    break;
                case 'none':
                    $this->docArray['uniqueDocList'][$key]['title'] = '';
                    break;
            }
        }
    }

    /** Création de la phrase contenant le titre, le nom et le prénom du pro de santé puis ajout dans $docArray['uniqueDocList'][$key]['fullNameSentence']
     * * Dépend de la présence ou l'asbence du titre de Dr
     * * Dépend de la présence ou l'absence du prénom
     */
    private function docFullNameSentenceCreator()
    {
        foreach ($this->docArray['uniqueDocList'] as $key => $value) {
            // on vérifie le titre, seuls les cas des 'dr' et des 'none' sont vraiment ciblés
            if ($value['title'] == 'Dr ') {
                $titleType = 'dr';
            } elseif ($value['title'] == '') {
                $titleType = 'none';
            } else {
                $titleType = 'other';
            }

            // les tests se déclenchent uniquement si ce n'est pas un docID == 0
            if ($value['docID'] != '0') {
                switch ($titleType) {
                    case 'dr':
                        if (strlen($value['firstName']) > 0) {
                            // si le title est 'dr' et qu'il y a un prénom alors 'fullNameSentence' = title + prénom + nom
                            $this->docArray['uniqueDocList'][$key]['fullNameSentence'] =
                            $this->docArray['uniqueDocList'][$key]['title'] .
                            ucwords($this->docArray['uniqueDocList'][$key]['firstName']) .
                            ' ' .
                            ucwords($this->docArray['uniqueDocList'][$key]['lastName']);
                        } else {
                            // si title est 'dr' mais qu'il n'y a pas de prénom alors 'fullNameSentence' = title + nom
                            $this->docArray['uniqueDocList'][$key]['fullNameSentence'] =
                            $this->docArray['uniqueDocList'][$key]['title'] .
                            ucwords($this->docArray['uniqueDocList'][$key]['lastName']);
                        }
                        break;

                    case 'other':
                        if (strlen($value['firstName']) > 0) {
                            // si pas 'dr' mais présence de prénom alors 'fullNameSentence' = prénom + nom
                            $this->docArray['uniqueDocList'][$key]['fullNameSentence'] =
                            ucwords($this->docArray['uniqueDocList'][$key]['firstName']) .
                            ' ' .
                            ucwords($this->docArray['uniqueDocList'][$key]['lastName']);
                        } else {
                            // si pas de 'dr' mais absence de prénom alors 'fullNameSentence' = title + nom
                            $this->docArray['uniqueDocList'][$key]['fullNameSentence'] =
                            $this->docArray['uniqueDocList'][$key]['title'] .
                            ucwords($this->docArray['uniqueDocList'][$key]['lastName']);
                        }
                        break;

                    case 'none':
                        if (strlen($value['firstName']) > 0) {
                            // si le title est 'none' et qu'il y a un prénom alors 'fullNameSentence' = prénom + nom
                            $this->docArray['uniqueDocList'][$key]['fullNameSentence'] =
                            $this->docArray['uniqueDocList'][$key]['firstName'] .
                            ' ' .
                            $this->docArray['uniqueDocList'][$key]['lastName'];
                        } else {
                            // si le title est 'none' et qu'il n'y pas de prénom  alors 'fullNameSentence' = nom
                            $this->docArray['uniqueDocList'][$key]['fullNameSentence'] =
                            $this->docArray['uniqueDocList'][$key]['lastName'];
                        }
                        break;
                }
            }
        }
    }

    /** Ajout des spécialités médicales de doc dans $docArray['uniqueDocList'][$docKey]['speMedicList'][]
     */
    private function docSpeMedicAdder()
    {
        foreach ($this->docArray['uniqueDocList'] as $docKey => $docValue) {
            foreach ($this->speMedicList as $speKey => $speValue) {
                if ($docValue['docID'] == $speValue['docID']) {
                    $tempArray['speMedicID'] = $speValue['speMedicID'];
                    $tempArray['name'] = $speValue['name'];
                    array_push($this->docArray['uniqueDocList'][$docKey]['speMedicList'], $tempArray);
                }
            }
        }
    }
}
