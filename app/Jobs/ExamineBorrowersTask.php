<?php

namespace App\Jobs;

use App\Helpers\StatusHelper;
use App\Helpers\VerificationHelper;
use App\Model\Borrower;
use App\Model\BorrowerLoan;
use App\Model\VerifiedDebtor;
use App\Model\VerifiedRestricted;
use App\Model\VerifiedTaxpayer;
use App\Services\LoanService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExamineBorrowersTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Производим обработку заёмщиков
     *
     * @return void
     */
    public function handle()
    {
        //в бан листе
        $borrowers = Borrower::withBanned()->get();
        foreach ($borrowers as $borrower) {
            if (time() > strtotime($borrower->unlock_date)) {
                $borrower->update([
                    "is_banned" => 0
                ]);
            }
        }
    }
}
