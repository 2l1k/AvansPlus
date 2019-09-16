<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    protected $email_data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $subject = $this->data["subject"] ?? 'Уведомление';
        $template = $this->data["template"] ?? 'emails.notification';
        return $this
                ->subject($subject)
                ->view($template, $this->data);
    }
}
