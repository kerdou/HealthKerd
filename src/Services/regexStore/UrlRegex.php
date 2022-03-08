<?php

namespace HealthKerd\Services\regexStore;

class UrlRegex
{
    public function __destruct()
    {
    }

    public function urlRegex(string $stringToCheck): bool
    {
        $stringToCheck = html_entity_decode($stringToCheck);

        $regexForURL = "((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9.\-]+|(?:www.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9.\-]+)((?:\/[\+~%\/.\w\-_]*)?\??(?:[\-\+=&;%@.\w_]*)#? (?:[\w]*))?)";
        $pregListForURL = "/" . $regexForURL . "/i";
        $regexResult = (preg_match($pregListForURL, $stringToCheck) ? true : false);

        return $regexResult;
    }
}
