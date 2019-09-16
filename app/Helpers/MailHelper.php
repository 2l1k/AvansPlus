<?php

namespace App\Helpers;


use App\Helpers\AppHelper;
use App\Mail\Admin\NewLoan;
use App\Mail\Agreement;
use App\Mail\Notification;
use Mail;

class MailHelper
{
    /**
     * Отправка email
     *
     * @param $template
     * @param $email_data
     */
    public static function sendMail($template, $email_data)
    {

        $result = Mail::send($template, $email_data, function ($message) use ($email_data) {
            if (!empty($email_data["to"])) {
                $message->to($email_data["to"], ($email_data["to_name"] ?? null));
            }
            if (!empty($email_data["subject"])) {
                $message->subject($email_data["subject"]);
            }
            if (!empty($email_data["sender"])) {
                $message->sender($email_data["sender"], ($email_data["sender_name"] ?? null));
            }


            if (!empty($email_data["attachments"])) {
                foreach ($email_data["attachments"] as $attachment) {
                    $message->attach($attachment);
                }
            }

//            $message->attach($pathToFile, array $options = []);
//            $message->from($address, $name = null);
//            $message->sender($address, $name = null);
//            $message->to($address, $name = null);
//            $message->cc($address, $name = null);
//            $message->bcc($address, $name = null);
//            $message->replyTo($address, $name = null);
//            $message->subject($subject);
//            $message->priority($level);
        });
        return $result;
    }

    public static function sendAgreement($mail_data)
    {
        return Mail::to($mail_data["to"])->send(new Agreement($mail_data));
    }

    public static function sendNotification($mail_data)
    {
        return Mail::to($mail_data["to"])->send(new Notification($mail_data));
    }

    public static function sendAdminNotificationNewLoan($mail_data)
    {
        return Mail::to(AppHelper::getConfig("admin_email"))->send(new NewLoan($mail_data));
    }

}
