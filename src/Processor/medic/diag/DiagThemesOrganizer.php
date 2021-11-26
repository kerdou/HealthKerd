<?php

namespace HealthKerd\Processor\medic\diag;

class DiagThemesOrganizer
{
    private array|null $diagMedicThemesRelation = array();
    private array|null $medicThemeList = array();

    /**
     *
     */
    public function diagThemesGeneralBuildOrder(
        array|null $diagMedicThemesRelation,
        array|null $medicThemeList
    ) {
        $this->diagMedicThemesRelation = $diagMedicThemesRelation;
        $this->medicThemeList = $medicThemeList;

        $this->diagThemeMerge();

        //var_dump($this->diagMedicThemesRelation);
        return $this->diagMedicThemesRelation;
    }


    private function diagThemeMerge()
    {
        foreach ($this->diagMedicThemesRelation as $diagKey => $diagValue) {
            foreach ($this->medicThemeList as $themeKey => $themeValue) {
                if ($diagValue['medicThemeID'] == $themeValue['medicThemeID']) {
                    $this->diagMedicThemesRelation[$diagKey]['themeName'] = $themeValue['name'];
                }

            }
        }

    }
}
