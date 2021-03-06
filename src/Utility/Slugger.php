<?php


namespace App\Utility;


class Slugger
{
    public static function slugify(string $string): string
    {
        //sanitize string
        $string = preg_replace('/[$&+,:;=?@#|\'\/<>.^*()%!-,][ ]+/','', $string);
        return preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
    }
}