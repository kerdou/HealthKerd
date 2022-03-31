<?php

namespace HealthKerd\Services\common;

use DateTimeZone;

/** Réorganisation et remplissage des données à l'échelon 'event' et pas les contenus */
class DateAndTimeManagement
{
    public function __destruct()
    {
    }

    /** Construction de données de temps pour la journée actuelle accessibles depuis $_ENV['DATEANDTIME']
     */
    public static function envDateSetter(): void
    {
        $_ENV['DATEANDTIME']['timezoneObj'] = timezone_open('Europe/Paris');

        $_ENV['DATEANDTIME']['nowDate']['nowTimeObj'] = date_create('now', $_ENV['DATEANDTIME']['timezoneObj']);
        $_ENV['DATEANDTIME']['nowDate']['nowTimestamp'] = date_timestamp_get($_ENV['DATEANDTIME']['nowDate']['nowTimeObj']);

        $_ENV['DATEANDTIME']['todayData']['earlyTimeObj'] = date_time_set($_ENV['DATEANDTIME']['nowDate']['nowTimeObj'], 0, 0, 0, 0);
        $_ENV['DATEANDTIME']['todayData']['earlyTimestamp'] = date_timestamp_get($_ENV['DATEANDTIME']['todayData']['earlyTimeObj']);

        $_ENV['DATEANDTIME']['todayData']['lateTimeObj'] = date_time_set($_ENV['DATEANDTIME']['nowDate']['nowTimeObj'], 23, 59, 59, 999999);
        $_ENV['DATEANDTIME']['todayData']['lateTimestamp'] = date_timestamp_get($_ENV['DATEANDTIME']['todayData']['lateTimeObj']);
    }

    /** Conversion des dates et heure
     * * Création de timestamp
     * * Création de date sous forme de phrase française. Ex: Lundi 28 Janvier 2022
     * * Création d'heure au format HH:MM
     * @param string $dateTime              Phrase brute de la date et de l'heure
     * @param DateTimeZone $timezoneObj     Objet du fuseau horaire
     * @return array                        Ensemble des données transformées
     */
    public function dateAndTimeConverter(string $dateTime, DateTimeZone $timezoneObj)
    {
        $initialDateObj = date_create($dateTime, $timezoneObj);
        $dateTimestamp = date_timestamp_get($initialDateObj);

        $results['timestamp'] = $dateTimestamp;

        // date formatée au format français
        $dateFormatObj = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        $results['frenchDate'] = ucwords($dateFormatObj->format($dateTimestamp));

        // heure formatée au format français
        $dateFormatObj = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::NONE, \IntlDateFormatter::SHORT);
        $results['time'] = $dateFormatObj->format($dateTimestamp);

        return $results;
    }
}
