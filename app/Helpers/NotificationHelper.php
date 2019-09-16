<?php

namespace App\Helpers;


use App\Helpers\AppHelper;
use App\Mail\Agreement;
use App\Mail\Notification;
use Mail;

class NotificationHelper
{
    /**
     * Отправка уведомления
     *
     * @param $template
     * @param $email_data
     */
    public static function sendNotification($action, $data)
    {

        switch ($action) {
            case "application_accepted": //Заявка принята
                $data["subject"] = "Ваша заявка принята";
                $data["template"] = "emails.notification.application_accepted";
                break;
            case "loading_documents": //Заявка предварительно одобрена
                $data["subject"] = "Заявка на рассмотрении";
                $data["template"] = "emails.notification.loading_documents";
                break;
//            case "contract_sent"://Договор отправлен заёмщику
//                $data["subject"] = "Подписание договора";
//                break;
            case "loan_issued": //Займ выдан
                $data["subject"] = "Займ выдан";
                $data["template"] = "emails.notification.loan_issued";
                break;
            case "loan_almost_overdue": //Займ почти просрочен
                $data["subject"] = "Напоминаем о просрочке вашего займа";
                $data["template"] = "emails.notification.loan_almost_overdue";
                break;
            case "refused": //В займе отказано
                $data["subject"] = "Извините, вам отказано в займе";
                $data["template"] = "emails.notification.loan_refused";
                break;
        }

        MailHelper::sendNotification($data);
    }

}
