<?php

namespace App\Helpers;

use Log;

class AppHelper
{
    static function getConfig($param = null) {
        if(isset($param)){
            return config("app.app_config.{$param}");
        }else{
            return config("app.app_config");
        }
    }

    static function checkValidIIN($iin)
    {
        $s = 0;
        for ($i = 0; $i < 11; $i++)
        {
            $s = $s + ($i + 1) * $iin{$i};
        }
        $k = $s % 11;
        if ($k == 10)
        {
            $s = 0;
            for ($i = 0; $i < 11; $i++)
            {
                $t = ($i + 3) % 11;
                if($t == 0)
                {
                    $t = 11;
                }
                $s = $s + $t * $iin{$i};
            }
            $k = $s % 11;
            if ($k == 10)
                return false;

            return ($k == substr($iin,11,1));
        }
        return ($k == substr($iin,11,1));
    }

    static function toNumeric($value) {
        return preg_replace("/[^0-9]/", '', $value);
    }

    static function randomPassword() {
        $alphabet = '1234567890';
        //$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    static function transliterate($textcyr = null, $textlat = null) {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];
        if($textcyr) return str_replace($cyr, $lat, $textcyr);
        else if($textlat) return str_replace($lat, $cyr, $textlat);
        else return null;
    }


}
