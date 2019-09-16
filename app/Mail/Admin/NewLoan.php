<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewLoan extends Mailable
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
                ->subject('Новая заявка №' . $this->email_data["loan"]->loan_id)
                ->view('emails.admin_notification.new_loan', $this->email_data);
    }
}
