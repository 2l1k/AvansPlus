<?php

namespace App\Services;

use App\Helpers\AppHelper;
use App\Model\PaymentOrder;

class PaymentService extends BaseService
{

    private $model;

    public function __construct(PaymentOrder $paymentOrder)
    {
        $this->model = $paymentOrder;
    }

    public function getOrderByTXNID($txn_id)
    {
        return $this->model->whereHas("qiwiTransactions", function($query) use ($txn_id){
            $query->where("txn_id", $txn_id);
        })->get()
        ->first();

    }

    public function getLastActiveOrder($loan_id)
    {
        return $this->model->withActive()
            ->where("borrower_loan_id", $loan_id)
            ->get()
            ->first();
    }

    public function cancelAllOrdersByLoanId($loan_id)
    {
        $this->model->withActive()
            ->where("borrower_loan_id", $loan_id)
            ->update(["is_active" => 0]);
    }

    public function addNewOrder($order_info)
    {
        return $this->model->create($order_info);
    }

}