<?php

namespace HealthKerd\Processor\medic\doc;

/** Classe de modification des données des docteurs */
class DocListFunctionsPool
{
    public function __destruct()
    {
    }

    /** Remplacement du titre du pro de santé pour être plus présentable, placé dans $docArray['uniqueDocList'][$key]['title']
     * * Etape indispensable avant de lancer docFullNameSentenceCreator()
     */
    protected function docTitleConversion()
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
    protected function docFullNameSentenceCreator()
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
    protected function docSpeMedicAdder()
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
