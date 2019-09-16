<?php

namespace App\Http\Controllers\Loan;

use Admin\Http\Controllers\Controller;
use App\Helpers\AppHelper;
use App\Helpers\StatusHelper;
use App\Model\PaymentOrder;
use App\Presenters\LoanPresenter;
use App\Services\HistoryService;
use App\Services\LoanService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Log;

class CloudpaymentsController extends Controller
{

    /**
     * Выполняется после того, как держатель заполнил платежную форму и нажал кнопку «Оплатить».
     *
     * RETURN:
     * 0    Платеж может быть проведен    Система выполнит авторизацию платежа
     * 10    Неверный номер заказа    Платеж будет отклонен
     * 11    Неверная сумма    Платеж будет отклонен
     * 13    Платеж не может быть принят    Платеж будет отклонен
     * 20    Платеж просрочен    Платеж будет отклонен, плательщик получит соответствующее уведомление
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function check(PaymentService $paymentService, Request $request)
    {
        $response_data = [];
        $this->addToLog($request, "CHECK");
        if ($this->checkHMAC($request)) {

            parse_str(file_get_contents('php://input'), $request_data);

            $request_amount = $request_data["Amount"];

            $order = PaymentOrder::find($request_data["InvoiceId"]);
            if (!empty($order->borrower_loan_id)) {

                $borrower = $order->borrower;

                $active_loan = $borrower->borrowerLoans()->withPossiblePayment()->activeLoan()->first();
                $loanPresenter = new LoanPresenter($active_loan);

                $pay_type = $order->pay_type;

                //Отменяем все предыдущие заказы
                $paymentService->cancelAllOrdersByLoanId($active_loan->id);

                //Добавляем новый заказ
                if ($pay_type == "repayment") {
                    $order_sum = $loanPresenter->amountMaturity();
                } else {
                    $order_sum = $loanPresenter->amountExtension();
                }
                $paymentService->addNewOrder([
                    "borrower_id" => $borrower->id,
                    "borrower_loan_id" => $active_loan->id,
                    "order_sum" => $order_sum,
                    "pay_type" => $pay_type,
                    "is_active" => 1
                ]);

                //Если сумма в запросе равна текущей сумме оплаты
                if (ceil($order_sum) == ceil($request_amount)) {
                    $response_data["code"] = 0;
                }

            }
        }


        $this->addToLog($request, "CHECK");
        return response()->json($response_data);
    }


    /**
     * Выполняется после того, как оплата была успешно проведена — получена авторизация эмитента.
     *
     * 0    Платеж зарегистрирован
     *
     * @param PaymentService $paymentService
     * @param LoanService $loanService
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(PaymentService $paymentService, LoanService $loanService, HistoryService $historyService, Request $request)
    {
        $response_data = [];
        if ($this->checkHMAC($request)) {

            parse_str(file_get_contents('php://input'), $request_data);
            $request_amount = $request_data["Amount"];
            $request_payment_amount = $request_data["PaymentAmount"];
            $order = PaymentOrder::find($request_data["InvoiceId"]);

            if (!empty($order->borrower_loan_id)) {

                $borrower = $order->borrower;

                $active_loan = $borrower->borrowerLoans()->withPossiblePayment()->activeLoan()->first();
                $loanPresenter = new LoanPresenter($active_loan);

                $order->borrower_loan_id;
                $order->order_sum;

                $pay_type = $order->pay_type;
                $order_sum = $order->order_sum;
                $paid_sum = $request_payment_amount;

                $order_new_info = [];
                $order_new_info["pay_key"] = 'cloudpayments';
                $order_new_info["paid_sum"] = $paid_sum;


                //Если сумма в запросе равна текущей сумме оплаты
                if (ceil($order_sum) == ceil($request_amount)) {
                    $order_new_info["is_paid"] = 1; //ставим метку - заявка оплачена
                    $order_new_info["is_active"] = 0; //снимаем метку активной заявки
                }

                $order->fill($order_new_info)->save();

                $loan_new_info = [];

                if ($pay_type == "repayment") {
                    $loan_new_info["paid_sum"] = $active_loan->paid_sum + $paid_sum; // оплаченная сумма
                }
                if ($pay_type == "extend") {
                    $loan_extension_data = $loanService->calculateLoanData($active_loan->sum, $active_loan->duration_agreement);
                    $loan_new_info["duration_actual"] = 1;
                    $loan_new_info["extension_date"] = $loan_extension_data["issue_date"];
                    $loan_new_info["expiration_date"] = $loan_extension_data["expiration_date"];
                    $loan_new_info["reward_sum"] = $loan_extension_data["reward_sum"]; // сбрасываем сумму вознаграждения
                    $loan_new_info["fine_sum"] = 0; // сбрасываем сумму просрочки
                    $loan_new_info["dealy_days"] = 0; // сбрасываем количество дней просрочки
                    $loan_new_info["penalty_sum"] = 0; // сбрасываем сумму начисленного штрафа
                    $loan_new_info["notary_sum"] = 0; // сбрасываем сумму начисленного штрафа
                    $loan_new_info["judgment_sum"] = 0; // сбрасываем сумму начисленного штрафа
                }

                $active_loan->fill($loan_new_info);
                $active_loan->save();

                if ($order->pay_type == "repayment") {
                    $loan_new_status_id = StatusHelper::LOAN_PAID; //оплачено
                } else {
                    $loan_new_status_id = StatusHelper::LOAN_EXTENDED; //продлён
                }

                //Если сумма в запросе равна текущей сумме оплаты
                if (ceil($order->order_sum) == ceil($request_amount)) {
                    //Меняем статус займа
                    $active_loan->changeStatus($loan_new_status_id);
                    $response_data["code"] = 0;
                }


                $pay_status_text = ($pay_type == "repayment") ? "Займ оплачен." : "Займ продлен.";
                $historyService->add([
                    "borrower_loan_id" => $active_loan->id,
                    "text" => "Оплата через Cloudpayments. \nСумма: " . ceil($order->order_sum) . " тг. \n{$pay_status_text}",
                    "history_key" => "client_history, cloudpayments_transaction, system",
                ]);

                $response_data["code"] = 0;
            }
        }

        $this->addToLog($request, "PAY");
        return response()->json($response_data);
    }


    /**
     * Выполняется в случае, если оплата была отклонена и используется для анализа количества и причин отказов.
     *
     * 0    Попытка зарегистрирована
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fail(Request $request)
    {
        $this->addToLog($request, "FAIL");
        $response_data["code"] = 0;
        return response()->json($response_data);
    }

    /**
     * Проверка подлинности пришедших данных
     *
     * @param $request
     * @return bool
     */
    public function checkHMAC($request)
    {
        $header_HMAC = $request->header("Content-HMAC");
        $query_string = file_get_contents('php://input');
        parse_str($query_string, $request_data);
        $generated_HMAC = base64_encode(hash_hmac('sha256', $query_string, AppHelper::getConfig("cloudpayments_api"), true));
        return $generated_HMAC == $header_HMAC;
    }


    public function addToLog($request, $action)
    {

        $message = file_get_contents('php://input');
        $response_data = [];
        $info = $action;
        $info .= "\n\r";
        $info .= $message . "_________";
//        $info .= "\n\r";
//        $info .= json_encode($request->query(), JSON_UNESCAPED_UNICODE);
//        $info .= "\n\r";
//        $info .= $request->getContent();
        $info .= "\n\r";
        $info .= json_encode($request->header("Content-HMAC"), JSON_UNESCAPED_UNICODE);
//        $info .= "\n\r";
//        $info .= json_encode($request->server(), JSON_UNESCAPED_UNICODE);
        $info .= "\n\r";
        $info .= base64_encode(hash_hmac('sha256', $message, AppHelper::getConfig("cloudpayments_api"), true));
        $info .= "\n\r";
        $info .= "\n\r";

        Log::info($info);
    }
}
