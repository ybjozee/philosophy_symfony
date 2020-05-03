<?php


namespace App\Utility;


use DateTime;
use DateTimeZone;

class DateTimeUtility
{
    public static function getCurrentDateTime(): DateTime
    {
        return new DateTime('now', new DateTimeZone('Africa/Lagos'));
    }

    public static function createDateTimeFromString($inputString): ?DateTime
    {
        return DateTime::createFromFormat('d/m/Y', $inputString, new DateTimeZone('Africa/Lagos'));
    }
}