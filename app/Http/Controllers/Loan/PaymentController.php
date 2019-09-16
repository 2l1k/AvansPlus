<?php

namespace App\Http\Controllers\Loan;

use Admin\Http\Controllers\Controller;
use App\Model\PaymentOrder;
use \App\Presenters\LoanPresenter;
use App\Services\BorrowerService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Страница полного погашения займа
     *
     * @return \Illuminate\Http\Response
     */

    public function repayment(BorrowerService $borrowerService, PaymentService $paymentService)
    {

        $borrower = $borrowerService->borrower();
        $active_loan = $borrower->borrowerLoans()->activeLoan()->first();
        $active_loan_status_id = ($active_loan) ? $active_loan->loan_status_id : false;

        //Если имеется действующий займ
        if($active_loan_status_id)
        {
            $loanPresenter = new LoanPresenter($active_loan);

            //Отменяем все предыдущие заказы
            $paymentService->cancelAllOrdersByLoanId($active_loan->id);

            //Добавляем новый заказ на полное погашение
            $payment_order = $paymentService->addNewOrder([
                "borrower_id" => $borrower->id,
                "borrower_loan_id" => $active_loan->id,
                "order_sum" => $loanPresenter->amountMaturity(),
                "pay_type" => "repayment",
                "is_active" => 1
            ]);

            return view('payment.repayment', [
                'borrower' => $borrower,
                'payment_order' => $payment_order,
                'active_loan' => $loanPresenter,
                'active_loan_status_id' => $active_loan_status_id
            ]);
        }else{
            return redirect(route("account.index"));
        }



    }

    /**
     * Страница продления займа
     *
     * @return \Illuminate\Http\Response
     */

    public function extend(BorrowerService $borrowerService, PaymentService $paymentService, Request $request)
    {
        $borrower = $borrowerService->borrower();
        $active_loan = $borrower->borrowerLoans()->activeLoan()->first();
        $active_loan_status_id = ($active_loan) ? $active_loan->loan_status_id : false;
        $loanPresenter = new LoanPresenter($active_loan);

        //Если имеется действующий займ
        if($active_loan_status_id) {
            //Отменяем все предыдущие заказы
            $paymentService->cancelAllOrdersByLoanId($active_loan->id);

            //Добавляем новый заказ на полное погашение
            $payment_order = $paymentService->addNewOrder([
                "borrower_id" => $borrower->id,
                "borrower_loan_id" => $active_loan->id,
                "order_sum" => $loanPresenter->amountExtension(),
                "pay_type" => "extend",
                "is_active" => 1
            ]);

            return view('payment.extend', [
                'borrower' => $borrower,
                'payment_order' => $payment_order,
                'active_loan' => $loanPresenter,
                'active_loan_status_id' => $active_loan_status_id
            ]);
        }else{
            return redirect(route("account.index"));
        }
    }

}
