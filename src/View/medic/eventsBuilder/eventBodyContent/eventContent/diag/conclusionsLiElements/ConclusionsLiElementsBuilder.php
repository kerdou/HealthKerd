<?php

namespace HealthKerd\View\medic\eventsBuilder\eventBodyContent\eventContent\diag\conclusionsLiElements;

class ConclusionsLiElementsBuilder
{
    public function __destruct()
    {
    }

    public function buildOrder(array $conclusionsData): string
    {
        $conclusionsHTML = '';

        if (sizeof($conclusionsData) > 0) {
            $affectBadgeTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/medicAffect/medicAffect.html');
            $sickStatusBadgeTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/badges/sickStatus/sickStatus.html');

            $conclusionLiTemplateHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/liElements/conclusions/conclusionTemplate.html');
            $conclusionLiElementsListHTML = '';

            foreach ($conclusionsData as $value) {
                $tempAffectBadgeHTML = $affectBadgeTemplateHTML;
                $tempAffectBadgeHTML = str_replace('{medicAffectName}', $value['medicAffectName'], $tempAffectBadgeHTML);

                $translatedSickStatus = $this->sickDetailedStatusTranslation($value['sickDetailedStatus']);
                $tempSickBadgeHTML = $sickStatusBadgeTemplateHTML;
                $tempSickBadgeHTML = str_replace('{sickStatusString}', $translatedSickStatus, $tempSickBadgeHTML);

                $tempConcluLiHTML = $conclusionLiTemplateHTML;
                $tempConcluLiHTML = str_replace('{affectBadge}', $tempAffectBadgeHTML, $tempConcluLiHTML);
                $tempConcluLiHTML = str_replace('{sickStatusBadge}', $tempSickBadgeHTML, $tempConcluLiHTML);

                $conclusionLiElementsListHTML .= $tempConcluLiHTML;
            }

            $conclusionsHTML = '<ul class="ps-0">' . $conclusionLiElementsListHTML . '</ul>';
        } else {
            $noConclusionLiHTML = file_get_contents($_ENV['APPROOTPATH'] . 'templates/loggedIn/medic/event/eventBodyContent/sections/eventContentAccordions/diagAccordion/liElements/conclusions/noConclusionTemplate.html');
            $conclusionsHTML = '<ul>' . $noConclusionLiHTML . '</ul>';
        }

        return $conclusionsHTML;
    }


    /** Conversion des strings de statut des maladies
     * @param string $sickDetailedStatus    Statut de la maladie
     * @return string                       Statut de maladie traduit
     */
    private function sickDetailedStatusTranslation(string $sickDetailedStatus): string
    {
        $sickStatusString = '';

        switch ($sickDetailedStatus) {
            case 'neverHad':
                $sickStatusString = 'Jamais eu';
                break;

            case 'suspected':
                $sickStatusString = 'Suspect??';
                break;

            case 'refuted':
                $sickStatusString = 'R??fut??';
                break;

            case 'confirmed':
                $sickStatusString = 'Confirm??';
                break;

            case 'stillSickButMonitored':
                $sickStatusString = 'Toujours affect?? et suivi';
                break;

            case 'healedButMonitored':
                $sickStatusString = 'Gu??ri mais surveill??';
                break;

            case 'healed':
                $sickStatusString = 'Gu??ri';
                break;
        }

        return $sickStatusString;
    }
}
