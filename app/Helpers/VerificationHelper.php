<?php

namespace App\Helpers;


use App\Helpers\AppHelper;
use App\Libraries\AntiСaptchaLib;
use App\Libraries\PHPQueryLib;

class VerificationHelper
{

    /**
     * Наличие ИИН в списке должников
     *
     * @param $iin
     * @return array
     */

    public static function findAmongDebtors($iin, $fio = "")
    {
        $result_array = [];

        //$html = self::curl('http://www.adilet.gov.kz/ru/kisa/erd');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, 'http://www.adilet.gov.kz/ru/kisa/erd');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $html = curl_exec($ch);
        curl_close($ch);

        $PQ = PHPQueryLib::newDocument($html);
        $captcha_sid = $PQ->find('[name="captcha_sid"]')->attr("value"); //captcha_sid
        $captcha_token = $PQ->find('[name="captcha_token"]')->attr("value"); //captcha_token
        $form_build_id = $PQ->find('[name="form_build_id"]')->eq(2)->attr("value"); //form_build_id
        $form_id = $PQ->find('[name="form_id"]')->eq(2)->attr("value"); //form_build_id
        $captcha_src = "http://www.adilet.gov.kz" . $PQ->find('[typeof="foaf:Image"]')->attr("src");

        $captcha_value = AntiСaptchaLib::getCaptchaValue($captcha_src);
        $result_code = AppHelper::toNumeric($captcha_value);

        if (!empty($result_code)) {

            $request_data = [];
            $request_data["fio"] = $fio;
            $request_data["iin"] = $iin;
            $request_data["form_id"] = "disa_search_form";
            $request_data["captcha_sid"] = $captcha_sid;
            $request_data["captcha_token"] = $captcha_token;
            $request_data["captcha_response"] = $result_code;
            $request_data["form_build_id"] = $form_build_id;
            $request_data["form_id"] = $form_id;

            $debtors_result_array2 = self::ajaxCurl('http://www.adilet.gov.kz/ru/system/ajax', $request_data);

            if (!empty($debtors_result_array2[1]) && !empty($debtors_result_array2[1]["data"])) {
                $html = $debtors_result_array2[1]["data"];
                $PQ = PHPQueryLib::newDocument("<html><head></head><body><div>{$html}</div></body></html>");
                $pure_table = $PQ->find('.pure-table')->html(); //captcha_sid

                if (!empty($pure_table)) {
                    $status = 1;
                    $json_data["pure_table"] = $pure_table;
                } else {
                    $status = 0;
                }

                $result_array = [
                    'result' => $html,
                    'pure_table' => $pure_table,
                    'status' => $status,
                ];
            }
        }

        return $result_array;
    }


    /**
     * Поиск в списке временно ограниченных на выезд
     *
     * @param $iin
     * @return array
     */

    public static function findAmongRestricted($iin, $fio = "")
    {
        $result_array = [];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, 'http://www.adilet.gov.kz/ru/kisa/zapret');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $html = curl_exec($ch);
        curl_close($ch);

        //$html = self::curl('http://www.adilet.gov.kz/ru/kisa/zapret');

        $PQ = PHPQueryLib::newDocument($html);
        $captcha_sid = $PQ->find('[name="captcha_sid"]')->attr("value"); //captcha_sid
        $captcha_token = $PQ->find('[name="captcha_token"]')->attr("value"); //captcha_token
        $form_build_id = $PQ->find('[name="form_build_id"]')->eq(2)->attr("value"); //form_build_id
        $form_id = $PQ->find('[name="form_id"]')->eq(2)->attr("value"); //form_build_id
        $captcha_src = "http://www.adilet.gov.kz" . $PQ->find('[typeof="foaf:Image"]')->attr("src");

        $captcha_value = AntiСaptchaLib::getCaptchaValue($captcha_src);
        $result_code = AppHelper::toNumeric($captcha_value);

        if (!empty($result_code)) {

            $request_data = [];
            $request_data["fio"] = $fio;
            $request_data["iin"] = $iin;
            $request_data["form_id"] = "disa_search_form";
            $request_data["captcha_sid"] = $captcha_sid;
            $request_data["captcha_token"] = $captcha_token;
            $request_data["captcha_response"] = $result_code;
            $request_data["form_build_id"] = $form_build_id;
            $request_data["form_id"] = $form_id;

            $debtors_result_array2 = self::ajaxCurl('http://www.adilet.gov.kz/ru/system/ajax', $request_data);

            if (!empty($debtors_result_array2[1]) && !empty($debtors_result_array2[1]["data"])) {
                $html = $debtors_result_array2[1]["data"];
                $PQ = PHPQueryLib::newDocument("<html><head></head><body><div>{$html}</div></body></html>");
                $pure_table = $PQ->find('.pure-table')->html(); //captcha_sid

                if (!empty($pure_table)) {
                    $status = 1;
                    $json_data["pure_table"] = $pure_table;
                } else {
                    $status = 0;
                }

                $result_array = [
                    'result' => $html,
                    'pure_table' => $pure_table,
                    'status' => $status,
                ];
            }
        }

        return $result_array;
    }


    /**
     * Наличие ИИН в списке налогоплательщиков
     *
     * @param $iin
     * @return array
     */

    public static function findAmongTaxpayers($iin, $fio = ""){

        $request_data = [];
        $request_data["rnn"] = "";
        $request_data["uin"] = $iin;
        $request_data["last_name"] = "";
        $request_data["first_name"] = "";
        $request_data["middle_name"] = "";
        $request_data["form_id"] = "taxpayer_search_individual";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, 'http://kgd.gov.kz/ru/services/taxpayer_search');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $html = curl_exec($ch);
        curl_close($ch);


        $PQ = PHPQueryLib::newDocument($html);
        $form_build_id = $PQ->find('[name="form_build_id"]')->eq(0)->attr("value");
        $request_data["form_build_id"] = $form_build_id;

        /* Формируем 2 идентификатора пользователя */
        $uid = "yxxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx";
        $time = time() * 1000;
        $generate_uid = preg_replace_callback(
            '/[xy]/',
            function ($matches) use ($time) {
                $c = $matches[0];
                $rand = mt_rand() / (mt_getrandmax() + 1);
                $r = ($time + $rand * 16) % 16 | 0;
                $time = floor($time / 16);
                return dechex(($c == "x" ? $r : ($r & 0x3 | 0x8)));
            },
            $uid
        );

        $time = time() * 1000;
        $generate_uid2 = preg_replace_callback(
            '/[xy]/',
            function ($matches) use ($time) {
                $c = $matches[0];
                $rand = mt_rand() / (mt_getrandmax() + 1);
                $r = ($time + $rand * 16) % 16 | 0;
                $time = floor($time / 16);
                return dechex(($c == "x" ? $r : ($r & 0x3 | 0x8)));
            },
            $uid
        );

        $captcha_src = "http://kgd.gov.kz/apps/services/CaptchaWeb/generate?uid=" . $generate_uid . "&t=" . $generate_uid2;

        $enterCaptcha = AntiСaptchaLib::getCaptchaValue($captcha_src);

        $result_array = [];
        if (!empty($enterCaptcha)) {

            $request_data["enterCaptcha"] = strtolower($enterCaptcha);
            $request_data["idCaptcha"] = $generate_uid;

            $taxpayer_result_array2 = self::ajaxCurl("http://kgd.gov.kz/ru/system/ajax", $request_data);

            if (!empty($taxpayer_result_array2[1]) && !empty($taxpayer_result_array2[1]["data"])) {
                if (!empty($taxpayer_result_array2[2]) && !empty($taxpayer_result_array2[2]["data"])) {
                    $html = $taxpayer_result_array2[2]["data"];
                } else {
                    $html = $taxpayer_result_array2[1]["data"];
                }
                $PQ = PHPQueryLib::newDocument("<html><head></head><body><div>{$html}</div></body></html>");
                $pure_table = $PQ->find('table')->html(); //captcha_sid
                $messages = $PQ->find('.messages')->html(); //captcha_sid

                if (!empty($pure_table)) {
                    $status = 1;
                    $json_data["pure_table"] = $pure_table;
                } else {
                    if (!empty($messages)) {
                        $json_data["error_table"] = $messages;
                    }
                    $status = 0;
                }

                $result_array = [
                    'result' => $html,
                    'pure_table' => $pure_table,
                    'status' => $status,
                ];
            }
        }

        return $result_array;

    }


    /**
     * @param $url
     * @param $request_data
     * @return array
     */
    public static function ajaxCurl($url, $request_data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_data));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: application/json, text/javascript, */*; q=0.01",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
        ));
        $results = curl_exec($ch) or die(curl_error($ch));
        curl_close($ch);

        return json_decode($results, true);
    }

    /**
     * @param $url
     * @param array $request_data
     * @return string
     */



    // public static function dcurl($url, $request_data = [])
    // {


    // $ch = curl_init();

    // curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    // curl_setopt($ch, CURLOPT_HEADER, 0);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_URL, 'http://kgd.gov.kz/ru/services/taxpayer_search');
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    // $data = curl_exec($ch);
    // curl_close($ch);

    // echo $data; exit();



    // $ch = curl_init('http://kgd.gov.kz/ru/services/taxpayer_search');
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    // //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_data));
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
    // curl_setopt($ch, CURLOPT_TIMEOUT, 40);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array (
    // "Accept: text/json",
    // 'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
    // ));
    // $results = curl_exec ($ch) or die(curl_error($ch));
    // $gov_page_html =$results;
    // echo $gov_page_html;  exit();





// //echo file_get_contents("http://kgd.gov.kz/ru/services/taxpayer_search"); exit();
    // $proxy = '95.59.26.99:65233';
    // $proxyauth = 'info:J6t9ScW';

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, "http://kgd.gov.kz/ru/services/taxpayer_search");
    // // curl_setopt($ch, CURLOPT_PROXY, $proxy);
    // // curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_HEADER, 1);
    // curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    // $curl_scraped_page = curl_exec($ch);
    // curl_close($ch);
// dd(1, $curl_scraped_page);
    // echo $curl_scraped_page;
    // }
    // public static function dcurl($url, $request_data = [])
    // {


    // $ch = curl_init($url);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    // if (!empty($request_data)) {
    // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_data));
    // }
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    // curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    // 'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
    // "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
    // ));

    // curl_setopt($ch, CURLOPT_PROXY, "185.120.76.76");
// curl_setopt($ch, CURLOPT_PROXYPORT, "65233");
// curl_setopt($ch, CURLOPT_PROXYUSERPWD, "info:J6t9ScW");

    // $html = curl_exec($ch) or die(curl_error($ch));
    // dd($ch);
    // curl_close($ch);

    // return $html;
    // }

}
