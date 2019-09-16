<?php

namespace App\Listeners;

use App\Events\LoanCreated;
use App\Helpers\MailHelper;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoanCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoanCreated  $event
     * @return void
     */
    public function handle(LoanCreated $event)
    {
        //Отправляем уведомление администратору о новом займе
        MailHelper::sendAdminNotificationNewLoan([
            "loan" => $event->borrowerLoan
        ]);
    }
}
