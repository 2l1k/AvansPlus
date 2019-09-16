<?php

namespace App\Helpers;


use App\Model\CommercialOffer;
use PDF;

class MathHelper
{
    /**
     * Преобразование цифры прописью
     *
     * @param $loan_id
     */
    public static function numberToString ($sourceNumber){
        //Целое значение $sourceNumber вывести прописью по-русски
        //Максимальное значение для аругмента-числа PHP_INT_MAX
        //Максимальное значение для аругмента-строки минус/плюс 999999999999999999999999999999999999
        $smallNumbers=array( //Числа 0..999
            array('ноль'),
            array('','один','два','три','четыре','пять','шесть','семь','восемь','девять'),
            array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать',
                'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать'),
            array('','','двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят','восемьдесят','девяносто'),
            array('','сто','двести','триста','четыреста','пятьсот','шестьсот','семьсот','восемьсот','девятьсот'),
            array('','одна','две')
        );
        $degrees=array(
            array('дофигальон','','а','ов'), //обозначение для степеней больше, чем в списке
            array('тысяч','а','и',''), //10^3
            array('миллион','','а','ов'), //10^6
            array('миллиард','','а','ов'), //10^9
            array('триллион','','а','ов'), //10^12
            array('квадриллион','','а','ов'), //10^15
            array('квинтиллион','','а','ов'), //10^18
            array('секстиллион','','а','ов'), //10^21
            array('септиллион','','а','ов'), //10^24
            array('октиллион','','а','ов'), //10^27
            array('нониллион','','а','ов'), //10^30
            array('дециллион','','а','ов') //10^33
            //досюда написано в Вики по нашей короткой шкале: https://ru.wikipedia.org/wiki/Именные_названия_степеней_тысячи
        );

        if ($sourceNumber==0) return $smallNumbers[0][0]; //Вернуть ноль
        $sign = '';
        if ($sourceNumber<0) {
            $sign = 'минус '; //Запомнить знак, если минус
            $sourceNumber = substr ($sourceNumber,1);
        }
        $result=array(); //Массив с результатом

        //Разложение строки на тройки цифр
        $digitGroups = array_reverse(str_split(str_pad($sourceNumber,ceil(strlen($sourceNumber)/3)*3,'0',STR_PAD_LEFT),3));
        foreach($digitGroups as $key=>$value){
            $result[$key]=array();
            //Преобразование трёхзначного числа прописью по-русски
            foreach ($digit=str_split($value) as $key3=>$value3) {
                if (!$value3) continue;
                else {
                    switch ($key3) {
                        case 0:
                            $result[$key][] = $smallNumbers[4][$value3];
                            break;
                        case 1:
                            if ($value3==1) {
                                $result[$key][]=$smallNumbers[2][$digit[2]];
                                break 2;
                            }
                            else $result[$key][]=$smallNumbers[3][$value3];
                            break;
                        case 2:
                            if (($key==1)&&($value3<=2)) $result[$key][]=$smallNumbers[5][$value3];
                            else $result[$key][]=$smallNumbers[1][$value3];
                            break;
                    }
                }
            }
            $value*=1;
            if (!$degrees[$key]) $degrees[$key]=reset($degrees);

            //Учесть окончание слов для русского языка
            if ($value && $key) {
                $index = 3;
                if (preg_match("/^[1]$|^\\d*[0,2-9][1]$/",$value)) $index = 1; //*1, но не *11
                else if (preg_match("/^[2-4]$|\\d*[0,2-9][2-4]$/",$value)) $index = 2; //*2-*4, но не *12-*14
                $result[$key][]=$degrees[$key][0].$degrees[$key][$index];
            }
            $result[$key]=implode(' ',$result[$key]);
        }

        return $sign.implode(' ',array_reverse($result));
    }


    /**
     * Строку в число
     *
     * @param $value
     * @return null|string|string[]
     */
    static function toNum($value)
    {
        return preg_replace("/[^0-9]/", '', $value);
    }
}
