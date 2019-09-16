<?php

namespace App\Presenters;


use App\Helpers\AppHelper;
use App\Helpers\StatusHelper;

class LoanPresenter extends Presenter
{
    /**
     * Является ли займ активным
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->model->is_active;
    }

    public function roundSum()
    {
        return ceil($this->model->sum);
    }

    public function availabelForRepayment()
    {
        return $this->loan_status_category_id == StatusHelper::CATEGORY_APPROVED;
    }


    /**
     * Сумма вознаграждения за указанный при регистрации период (сумма * процентную ставку * период)
     *
     * @return float
     */
    public function rewardSumForWholePeriod($duration_agreement = null)
    {
        $duration_agreement = ($duration_agreement != null) ? $duration_agreement : $this->model->duration_agreement;
        return ceil($this->model->sum * AppHelper::getConfig("interest_rate") * $duration_agreement);
    }


    /**
     * Сумма по векселю за указанный период
     *
     * @param null $duration_agreement
     * @param int $mrp_quantity
     * @return float
     */
    public function billSumForPeriod($duration_agreement = null, $mrp_quantity = 10)
    {
        $duration_agreement = ($duration_agreement != null) ? $duration_agreement : $this->model->duration_agreement;
        $mrp = AppHelper::getConfig("mrp") * $mrp_quantity;
        return ceil(
            $this->model->sum
            + $this->model->sum * AppHelper::getConfig("interest_rate") * $duration_agreement
            + $mrp
        );
    }

    /**
     * Дата по векселю за указанный период
     *
     * @return float
     */
    public function billDateForPeriod($duration_agreement = null)
    {
        $duration_agreement = ($duration_agreement != null) ? $duration_agreement : $this->model->duration_agreement;
        $expiration_date = date("d-m-Y", time() + $duration_agreement * 86400);
        return $expiration_date;
    }

    /**
     * Сумма встречного предложения
     *
     * @return float
     */
    public function roundCounterofferSum()
    {
        return ceil($this->model->counteroffer_sum);
    }

    /**
     * Сумма к погашению (
     * сумма займа
     * + сумма вознаграждения
     * + сумма вознагражденяи после просрочки
     * + Сумма начисленного единовременного штрафа)
     * + Сумма за нотариальную подпись
     * + Сумма за судебные услуги
     * - Погашенная сумма
     *
     * @return float
     */
    public function amountMaturity()
    {
        $result = ceil(
            $this->model->sum
            + $this->model->reward_sum
            + $this->model->fine_sum
            + $this->model->penalty_sum
            + $this->model->notary_sum
            + $this->model->judgment_sum
            - $this->model->paid_sum
        );
        return ($result > 0) ? $result : 0;
    }

    /**
     * Сумма вознаграждения + штраф и пр. (
     * сумма займа
     * + сумма вознаграждения
     * + сумма вознагражденяи после просрочки
     * + Сумма начисленного единовременного штрафа)
     * + Сумма за нотариальную подпись
     * + Сумма за судебные услуги
     *
     * @return float
     */
    public function amountMaturityWithoutPaidSum()
    {
        $result = ceil(
            $this->model->sum
            + $this->model->reward_sum
            + $this->model->fine_sum
            + $this->model->penalty_sum
            + $this->model->notary_sum
            + $this->model->judgment_sum
        );
        return ($result > 0) ? $result : 0;
    }

    /**
     * Сумма вознаграждения + штраф и пр. (
     * сумма займа
     * + сумма вознаграждения
     * + сумма вознагражденяи после просрочки
     * + Сумма начисленного единовременного штрафа)
     * + Сумма за нотариальную подпись
     * + Сумма за судебные услуги
     *
     * @return float
     */
    public function enforcementCost()
    {
        $result = ceil(
            + $this->model->penalty_sum
            + $this->model->notary_sum
            + $this->model->judgment_sum
        );
        return ($result > 0) ? $result : 0;
    }

    /**
     * Сумма к продлению (
     * + сумма вознаграждения
     * + сумма вознагражденяи после просрочки
     * + Сумма начисленного единовременного штрафа)
     * + Сумма за нотариальную подпись
     * + Сумма за судебные услуги
     * - Погашенная сумма
     *
     * @return float
     */
    public function amountExtension()
    {
        //Считаем по оплате, сколько было внесено за продление
        $payment_orders = $this->model->paymentOrders;
        $all_extend_sum = 0;
        foreach($payment_orders as $payment_order){
            $all_extend_sum += ($payment_order->pay_type == "extend" && $payment_order->paid_sum > 0) ?  $payment_order->paid_sum : 0;
        }

        $result = ceil(
             $this->model->reward_sum
            + $this->model->fine_sum
            + $this->model->penalty_sum
            + $this->model->notary_sum
            + $this->model->judgment_sum
            - $all_extend_sum
        );
        return ($result > 0) ? $result : 0;
    }




    /**
     * Является ли просроченным
     *
     * @return bool
     */
    public function isOverdue()
    {
        return time() > strtotime($this->model->expiration_date);
    }

    /**
     * Был ли одобрен
     *
     * @return bool
     */
    public function wasApproved()
    {
        return in_array($this->model->loan_status_category_id, [
            StatusHelper::CATEGORY_APPROVED,
            StatusHelper::CATEGORY_CLOSED
        ]);
    }

}