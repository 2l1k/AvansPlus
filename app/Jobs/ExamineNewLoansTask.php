<?php

namespace App\Jobs;

use App\Helpers\StatusHelper;
use App\Helpers\VerificationHelper;
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

class ExamineNewLoansTask implements ShouldQueue
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
     * Производим обработку новый займов
     *
     * @return void
     */
    public function handle()
    {
        $new_loans = BorrowerLoan::activeLoan()->withNew()->get();
        foreach ($new_loans as $loan) {

            //отказываем везде, где пользователь в бане
            if ($loan->borrower->is_banned == 1) {
                $loan_new_data = [];
                $loan_new_data["loan_status_id"] = StatusHelper::REFUSED;
                $loan_new_data["loan_status_category_id"] = StatusHelper::CATEGORY_REJECTED;
                $loan->fill($loan_new_data)
                    ->save();
            }
        }

    }

}
