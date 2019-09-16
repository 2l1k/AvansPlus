<?php

namespace App\Http\Controllers\Loan;

use Admin\Http\Controllers\Controller;
use App\Helpers\AppHelper;
use App\Helpers\StatusHelper;
use App\Model\Payment\QiwiTransaction;
use App\Presenters\LoanPresenter;
use App\Services\BorrowerService;
use App\Services\HistoryService;
use App\Services\LoanService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class QiwiController extends Controller
{

    protected $access_login = "avansplus_qiwi_login";
    protected $access_password = "8jNEYDFewr2";

    public function pay(BorrowerService $borrowerService, LoanService $loanService, PaymentService $paymentService, HistoryService $historyService, Request $request)
    {

        $content = '';
        $data = array();
        $login = $request->server('PHP_AUTH_USER');
        $password = $request->server('PHP_AUTH_PW');


        if ($login == $this->access_login && $password == $this->access_password) {

            $headers = [
                'Content-Type' => 'text/xml',
            ];
            $page_code = 200;

            $command = $request->input("command"); //Команда запроса
            $account = $request->input("account"); //Идетификатор клиента
            $txn_id = $request->input("txn_id"); //Идетификатор платежа
            $request_sum = $request->input("sum"); //Сумма платежа
            $txn_date = $request->input("txn_date"); //Дата учета платежа в системе ОСМП
            $txn_datetime_array = date_parse_from_format("YmdHis", $txn_date);
            $txn_datetime = date("Y-m-d H:i:s", strtotime("{$txn_datetime_array["year"]}-{$txn_datetime_array["month"]}-{$txn_datetime_array["day"]} {$txn_datetime_array["hour"]}:{$txn_datetime_array["minute"]}:{$txn_datetime_array["second"]}"));


            /*
            0 - ОК
            4 Неверный формат идентификатора абонента
            5 Идентификатор абонента не найден (Ошиблись номером)
            7 Прием платежа запрещен провайдером
            90 - Проведение платежа не окончено
            242 Сумма слишком велика
            243 Невозможно проверить состояние счета
            */

            //Проверяем формат номера
            $account = AppHelper::toNumeric($account);
            $is_phone_number = preg_match('/\d{11}/', $account);

            if ($is_phone_number) {
                $borrower = $borrowerService->borrowerByPhoneNumber($account);
                if (empty($borrower)) {
                    $result = 5;
                    $comment = "Клиент с указанным номером не найден. Проверьте корректность введенного номера.";
                } else {
                    $active_loan = $borrower->borrowerLoans()->withPossiblePayment()->activeLoan()->first();
                    if (!empty($active_loan)) {
                        $result = 0;
                        $loanPresenter = new LoanPresenter($active_loan);
                        $comment = "Клиент прошел проверку";

                        $last_active_order = $paymentService->getLastActiveOrder($active_loan->id);

                        //На этапе проверки мы отменяем предыдущие заказы и формируем новый, чтобы данные всегда были актуальными
                        if ($command == "check") {
                            //Находим последний заказ на оплату, чтобы определить, была ли заявка на продление или на полное погашение
                            $pay_type = "repayment";
                            if (!empty($last_active_order)) {
                                $pay_type = $last_active_order->pay_type;
                            }

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

                            $data["fields"] = [
                                "order_sum" => $order_sum
                            ];
                        } elseif ($command == "pay") {
                            $data["osmp_txn_id"] = $txn_id;

                            //Проведение платежа
                            if (!empty($last_active_order)) {

                                //Проверяем, был ли уже проведён платёж с данным $txn_id
                                if (!empty($txn_id)) {
                                    $txn_order = $paymentService->getOrderByTXNID($txn_id);
                                }

                                if (empty($txn_order)) {

                                    $order_id = $last_active_order->id;
                                    $data["prv_txn"] = $order_id; //уникальный номер операции пополнения баланса абонента

                                    $order_new_info = [];
                                    $order_new_info["pay_key"] = 'qiwi_terminal';

                                    $paid_sum = $last_active_order->paid_sum + $request_sum; // Прибавляем оплаченную сумму
                                    $order_new_info["paid_sum"] = $paid_sum;

                                    //Если сумма оплачена полностью, возвращаем положительный результат и меняем статус заказа и кредита
                                    if ($paid_sum != 0 && $paid_sum >= $last_active_order->order_sum) {
                                        $result = 0;
                                        $order_new_info["is_paid"] = 1; //ставим метку - заявка оплачена
                                        $order_new_info["is_active"] = 0; //снимаем метку активной заявки

                                        if ($last_active_order->order_sum == $paid_sum) {
                                            $transaction_status = "Сумма оплачена полностью";
                                        } else {
                                            $transaction_status = "Сумма оплаты превышена на " . ($paid_sum - $last_active_order->order_sum);
                                        }

                                        $loan_new_info = [];

                                        $loan_new_info["paid_sum"] = $active_loan->paid_sum + $paid_sum; // оплаченная сумма

                                        if ($last_active_order->pay_type == "extend") {
                                            $loan_extension_data = $loanService->calculateLoanData($active_loan->sum, $active_loan->duration_agreement);
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

                                        if ($last_active_order->pay_type == "repayment") {
                                            $loan_new_status_id = StatusHelper::LOAN_PAID; //оплачено
                                        } else {
                                            $loan_new_status_id = StatusHelper::LOAN_EXTENDED; //продлён
                                        }
                                        //Меняем статус займа
                                        $active_loan->changeStatus($loan_new_status_id);

                                        $pay_status_text = ($last_active_order->pay_type == "repayment") ? "Займ оплачен." : "Займ продлен.";
                                        $historyService->add([
                                            "borrower_loan_id" => $active_loan->id,
                                            "text" => "Оплата через QIWI терминал. \nСумма: " . ceil($last_active_order->order_sum) . " тг. \n{$pay_status_text}",
                                            "history_key" => "client_history, qiwi_transaction, system",
                                        ]);

                                    } else {
                                        $result = 0;
                                        $transaction_status = "Сумма оплачена не полностью";

                                        $loan_new_info = [];
                                        $loan_new_info["paid_sum"] = $active_loan->paid_sum + $request_sum; // оплаченная сумма

                                        $active_loan->fill($loan_new_info);
                                        $active_loan->save();

                                        $historyService->add([
                                            "borrower_loan_id" => $active_loan->id,
                                            "text" => "Внесено недостаточно средств для закрытия платежа.\nСумма: " . ceil($request_sum) . " тг. ",
                                            "history_key" => "client_history, qiwi_transaction, system",
                                        ]);
                                    }

                                    $order_new_info["comment"] = $comment = $transaction_status;

                                    //Обновляем инфомрацию в заказе на оплату
                                    $last_active_order->update($order_new_info);

                                    //Добавляем информацию о QIWI - транзакции
                                    $transaction_info = [];
                                    $transaction_info["payment_order_id"] = $last_active_order->id;
                                    $transaction_info["txn_id"] = $txn_id;
                                    $transaction_info["txn_date"] = $txn_datetime;
                                    $transaction_info["sum"] = $request_sum;

                                    QiwiTransaction::create($transaction_info);
                                } else {
                                    $data["prv_txn"] = $txn_order->id; //уникальный номер операции пополнения баланса абонента
                                    $result = 0;
                                    $comment = "Сумма оплачена не полностью";
                                }
                            } else {
//                                $order_id = $last_active_order->id;
//                                $data["prv_txn"] = $order_id; //уникальный номер операции пополнения баланса абонента
                                $result = 0;
                                $qiwi_transaction = QiwiTransaction::where("txn_id", $data["osmp_txn_id"])->first();
                                if (!empty($qiwi_transaction)) {
                                    $data["prv_txn"] = $qiwi_transaction->payment_order_id;
                                }
                                $comment = "Нет активных кредитов";
                            }
                        }
                    } else {
                        $result = 243;
                        $comment = "Нет активных кредитов";
                    }
                }
            } else {
                $result = 4;
                $comment = "Неверный формат идентификатора абонента";
            }

            $data["osmp_txn_id"] = $txn_id;
            $data["result"] = $result;
            $data["comment"] = $comment; //комментарий завершения операции
            $content = view('loan.qiwi.pay', $data);
        } else {

            $headers = [
                'WWW-Authenticate' => 'Basic realm="My Realm"'
            ];
            $page_code = 401;
        }


        return response($content, $page_code)->withHeaders($headers);
    }
}
