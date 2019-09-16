<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CronController extends Controller
{

    public function checkStatus(Request $request)
    {
        $borrower = $borrowerService->borrower();
        $active_loan = $borrower->borrowerLoans()->activeLoan()->first();
        return response()->json([
            "loan_status_id" => $active_loan->loan_status_id
        ]);
    }


    public function verification(Request $request)
    {
        //Добавляем в очередь задачу на парсинг данных с EGOV
        \App\Jobs\VerifyBorrowerTask::dispatch();
    }

}



