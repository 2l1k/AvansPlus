<?php

namespace App\Http\Controllers;

use App\Helpers\VerificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;


class TestController extends Controller
{


    public function test()
    {
        //Добавляем в очередь задачу на парсинг данных с EGOV
        //\App\Jobs\VerifyBorrowerTask::dispatch();

        //Производим обработку дейтсвующих займов
        \App\Jobs\ExamineActiveAgreementsTask::dispatch();

        //Производим обработку заёмщиков
        \App\Jobs\ExamineBorrowersTask::dispatch();

        //Производим обработку новых займов
        \App\Jobs\ExamineNewLoansTask::dispatch();


    }

    /**
     * Вроверяем в списке должников
     * @param Request $request
     */
    public function findDebtors(Request $request)
    {
        $iin = $request->input("iin");
        if(!empty($iin)){
            $debtors_result = VerificationHelper::findAmongDebtors($iin);
            dd($debtors_result);
        }
    }
    /**
     * Вроверяем в списке должников
     * @param Request $request
     */
    public function findTaxpayers(Request $request)
    {
        $iin = $request->input("iin");
        if(!empty($iin)){
            $debtors_result = VerificationHelper::findAmongTaxpayers($iin);
            dd($debtors_result);
        }
    }
    /**
     * Вроверяем в списке должников
     * @param Request $request
     */
    public function findRestricted (Request $request)
    {
        $iin = $request->input("iin");
        if(!empty($iin)){
            $restricted_result = VerificationHelper::findAmongRestricted($iin);
            dd($restricted_result);
        }
    }

}
