<?php 

namespace App\class;
use DateTime;

setlocale(LC_ALL, "pt-BR");

final class DateFormater {
    public static function data(string $date){
        $time = new DateTime($date);
        $date = $time->format("F j, Y");
        return $date;
    }
    public static function simpleData(string $date){
        $time = new DateTime($date);
        $date = $time->format("j \o\\f F, Y");
        return $date;
    }
    public static function hora(string $date){
        $time = new DateTime($date);
        $time = $time->format('g:i');

        return $time;
    }
}