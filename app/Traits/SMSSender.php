<?php
namespace App\Traits;

use App\Helpers\AppHelper;
use App\Http\Requests\Request;
use App\SMSRU;
use Log;
use Symfony\Component\Console\Helper\Helper;

trait SMSSender
{
    /**
     * Отправляет код подтверждения на указанный телефон
     *
     * @param $phone_number
     * @return array|bool|mixed|\stdClass
     */
    public function sendConfirmation($phone_number)
    {
        $sms_code_sending_time = session('sms_code_sending_time');
        if ($sms_code_sending_time + AppHelper::getConfig('sms_lifetime') < time()) {

            $sms_code = mt_rand(1000, 9999);

            session(['sms_code' => $sms_code]);
            session(['sms_code_sending_time' => time()]);

            $sms_request_data = collect();
            $sms_request_data->to = $phone_number;
            $sms_request_data->text = "Ваш код подтверждения {$sms_code}";

            //Отправляем СМС с кодом подверждения
            return $this->sendSMSToPhone($sms_request_data);

            return true;
        }else{
            return false;
        }
    }


    /**
     * Отправка кода восстановления
     *
     * @param $phone_number
     * @return bool
     */
    public function sendRecoveryCode($phone_number)
    {
        $sms_lifetime = AppHelper::getConfig("sms_lifetime");
        $sms_recovery_code_sending_time = session('sms_recovery_code_sending_time');

        if ($sms_recovery_code_sending_time +  $sms_lifetime < time()) {
            //Генерируем код восстановления
            $recovery_code = mt_rand(1000, 9999);
            session()->put("recovery_data", [
                "code" => $recovery_code,
                "phone" => $phone_number,
                "time" => time()
            ]);
            //Отправляем код в СМС сообщении
            $this->sendMessage($phone_number, "Код восстановления пароля: {$recovery_code}");

            return true;
        }else{
            return false;
        }
    }



    /**
     * Отправляет код подтверждения на указанный телефон
     *
     * @param $phone_number
     * @return array|bool|mixed|\stdClass
     */
    public function sendMessage($phone_number, $message)
    {
        $sms_request_data = collect();
        $sms_request_data->to = $phone_number;
        $sms_request_data->text = $message;

        return $this->sendSMSToPhone($sms_request_data);
    }

    /**
     * Отправляет сообщение на указанный номер
     *
     * @param $sms_request_data
     * @return array|mixed|\stdClass
     */
    static function sendSMSToPhone($sms_request_data){
        file_get_contents('http://kazinfoteh.org:9507/api?action=sendmessage&username=avans1&password=Bdl9Wqls4&recipient='.  $sms_request_data->to .'&messagetype=SMS:TEXT&originator=INFO_KAZ&messagedata='. AppHelper::transliterate($sms_request_data->text));
        $send_status = true;
        return $send_status;
    }

//    static function sendSMSToPhone($sms_request_data){
//        $smsru_api_id = AppHelper::getConfig("smsru_api_id");
//        $SMSRU = new SMSRU($smsru_api_id);
//        $sms_response_data = $SMSRU->send_one($sms_request_data);
//        $send_status = false;
//        if(is_object($sms_response_data) && $sms_response_data->status_code == 100){
//            $send_status = true;
//        }
//       // Log::info("SMS sent", [print_r($sms_response_data, true)]);
//        return $send_status;
//    }

}