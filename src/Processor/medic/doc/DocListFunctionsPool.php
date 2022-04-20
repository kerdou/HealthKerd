<?php

namespace HealthKerd\Processor\medic\doc;

/** Classe de modification des données des docteurs */
class DocListFunctionsPool
{
    public function __destruct()
    {
    }

    /** Ajout des spécialités médicales de doc dans $docArray['uniqueDocList'][$docKey]['speMedicList'][]
     */
    protected function docSpeMedicAdder()
    {
        foreach ($this->docArray['uniqueDocList'] as $docKey => $docValue) {
            foreach ($this->speMedicList as $speKey => $speValue) {
                if ($docValue['docID'] == $speValue['docID']) {
                    $tempArray['speMedicID'] = $speValue['speMedicID'];
                    $tempArray['nameForDoc'] = $speValue['nameForDoc'];
                    array_push($this->docArray['uniqueDocList'][$docKey]['speMedicList'], $tempArray);
                }
            }
        }
    }
}
