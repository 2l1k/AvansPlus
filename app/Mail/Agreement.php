<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Agreement extends Mailable
{
    use Queueable, SerializesModels;

    protected $email_data;

    public function __construct($email_data)
    {
        $this->email_data = $email_data;
    }

    public function build()
    {

        return $this
                ->attach(array_shift($this->email_data["attachments"]))
                ->subject('Договор залога №' . $this->email_data["loan"]->loan_id)
                ->view('emails.agreement', $this->email_data);
    }
}
