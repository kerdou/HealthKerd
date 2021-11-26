<?php

namespace HealthKerd\Processor\medic\presc;

class PrescOrdoPharmaOrganizer
{
    private array|null $prescPharmaList = array();
    private array|null $treatPharmaList = array();
    private array|null $treatPharmaRoleOnAffect = array();
    private array|null $medicAffectList = array();

    public function prescOrdoPharmaGeneralBuildOrder(
        array|null $prescPharmaList,
        array|null $treatPharmaList,
        array|null $treatPharmaRoleOnAffect,
        array|null $medicAffectList
    ) {
        $this->prescPharmaList = $prescPharmaList;
        $this->treatPharmaList = $treatPharmaList;
        $this->treatPharmaRoleOnAffect = $treatPharmaRoleOnAffect;
        $this->medicAffectList = $medicAffectList;

        $this->prescTreatContentOrganizer();
        $this->treatContentOrganizer();
        $this->roleContentOrganizer();
        $this->affectContentOrganizer();

        $this->roleAndAffectMerge();
        $this->prescAndRoleMerge();
        $this->prescAndTreatMerge();

        //var_dump($this->prescPharmaList);
        //var_dump($this->treatPharmaList);
        //var_dump($this->treatPharmaRoleOnAffect);
        //var_dump($this->medicAffectList);

        return $this->prescPharmaList;
    }

    private function prescTreatContentOrganizer()
    {
        $prescTempArray = array();

        foreach ($this->prescPharmaList as $value) {
            $tempArray = array();

            $tempArray['prescPharmaID'] = $value['prescPharmaID'];
            $tempArray['content'] = $value['content'];

            $tempArray['ordoPharmaID'] = $value['ordoPharmaID'];

            $tempArray['treatPharmaID'] = $value['treatPharmaID'];
            $tempArray['treatName'] = '';

            $tempArray['treatPharmaRoleID'] = $value['treatPharmaRoleID'];
            $tempArray['roleName'] = '';

            $tempArray['medicAffectID'] = '';
            $tempArray['affectName'] = '';

            $tempArray['comment'] = $value['comment'];

            array_push($prescTempArray, $tempArray);
        }

        $this->prescPharmaList = $prescTempArray;
    }


    private function treatContentOrganizer()
    {
        $treatTempArray = array();

        foreach ($this->treatPharmaList as $value) {
            $tempArray = array();

            $tempArray['treatPharmaID'] = $value['treatPharmaID'];
            $tempArray['treatName'] = $value['name'];

            array_push($treatTempArray, $tempArray);
        }

        $this->treatPharmaList = $treatTempArray;
    }


    private function roleContentOrganizer()
    {
        $roleTempArray = array();

        foreach ($this->treatPharmaRoleOnAffect as $value) {
            $tempArray = array();

            $tempArray['treatPharmaRoleID'] = $value['treatPharmaRoleID'];
            $tempArray['roleName'] = $value['name'];
            $tempArray['medicAffectID'] = $value['medicAffectID'];
            $tempArray['affectName'] = '';

            array_push($roleTempArray, $tempArray);
        }

        $this->treatPharmaRoleOnAffect = $roleTempArray;
    }


    private function affectContentOrganizer()
    {
        $affectTempArray = array();

        foreach ($this->medicAffectList as $value) {
            $tempArray = array();

            $tempArray['medicAffectID'] = $value['medicAffectID'];
            $tempArray['affectName'] = $value['name'];

            array_push($affectTempArray, $tempArray);
        }

        $this->medicAffectList = $affectTempArray;
    }


    private function roleAndAffectMerge()
    {
        foreach ($this->treatPharmaRoleOnAffect as $roleKey => $roleValue) {
            foreach ($this->medicAffectList as $affectKey => $affectValue) {
                if ($roleValue['medicAffectID'] == $affectValue['medicAffectID']) {
                    $this->treatPharmaRoleOnAffect[$roleKey]['affectName'] = $affectValue['affectName'];
                }
            }
        }
    }


    private function prescAndRoleMerge()
    {
        foreach ($this->prescPharmaList as $prescKey => $prescValue) {
            foreach ($this->treatPharmaRoleOnAffect as $roleKey => $roleValue) {
                if ($prescValue['treatPharmaRoleID'] == $roleValue['treatPharmaRoleID']) {
                    $this->prescPharmaList[$prescKey]['medicAffectID'] = $roleValue['medicAffectID'];
                    $this->prescPharmaList[$prescKey]['affectName'] = $roleValue['affectName'];
                    $this->prescPharmaList[$prescKey]['roleName'] = $roleValue['roleName'];
                }
            }
        }
    }


    private function prescAndTreatMerge()
    {
        foreach ($this->prescPharmaList as $prescKey => $prescValue) {
            foreach ($this->treatPharmaList as $treatKey => $treatValue) {
                if ($prescValue['treatPharmaID'] == $treatValue['treatPharmaID']) {
                    $this->prescPharmaList[$prescKey]['treatName'] = $treatValue['treatName'];
                }
            }
        }
    }
}
