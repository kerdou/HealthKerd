<?php

namespace HealthKerd\Services\regexStore;

class PwdRegex
{
    public function __destruct()
    {
    }

    public function pwdRegex(string $stringToCheck): array
    {
        $stringToCheck = html_entity_decode($stringToCheck);

        $pwdPreg = "(?'lower'([a-z]+)?)(?'upper'([A-Z]+)?)(?'nbr'([0-9]+)?)(?'spe'([\W]+)?)";
        $pregListForPwd = "/" . $pwdPreg . "/";
        preg_match_all($pregListForPwd, $stringToCheck, $regexResult, PREG_PATTERN_ORDER);

        $pwdSummary['length'] = strlen($stringToCheck);
        $pwdSummary['lower'] = $this->resultCounter($regexResult['lower']);
        $pwdSummary['upper'] = $this->resultCounter($regexResult['upper']);
        $pwdSummary['nbr'] = $this->resultCounter($regexResult['nbr']);
        $pwdSummary['spe'] = $this->resultCounter($regexResult['spe']);

        return $pwdSummary;
    }

    private function resultCounter(array $array): int
    {
        $counter = 0;

        foreach ($array as $value) {
            $value != '' ? $counter++ : '';
        }

        return $counter;
    }
}
