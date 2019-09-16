<?php

namespace App\Services;

use App\Helpers\AppHelper;
use App\Helpers\StatusHelper;
use App\Model\LoanStatus;
use App\Repositories\LoanRepository;

class LoanService extends BaseService
{
    private $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function loan($Loan_id = false)
    {
        $Loan_id = ($Loan_id !== false) ? $Loan_id : session("Loan_id");
        return $this->loanRepository->find($Loan_id);
    }

    /**
     * Формирование первоначальной информации о займе по сумме и сроку
     *
     * @param $sum
     * @param $duration_agreement
     * @return array
     */
    public function buildLoanRegistrationData($sum, $duration_agreement)
    {
        $loan_data = [];
        $config = AppHelper::getConfig();
        $interest_rate = $config["interest_rate"]; //процентная ставка;
        $fine_interest_rate = $config["fine_interest_rate"]; //процентная ставка;

       // $reward_on_day = $sum * $interest_rate; //ставка вознаграждения в день
        $loan_data["sum"] = $sum; //Процентная ставка;
        $loan_data["counteroffer_sum"] = $sum; //Сумма встречного предложения;
        $loan_data["paid_sum"] = 0; //Погашенная сумма;
        $loan_data["duration_agreement"] = $duration_agreement; //Срок, на который оформляется займ;
        $loan_data["counteroffer_duration_agreement"] = $duration_agreement; //Срок встречного предложения;
        $loan_data["issue_date"] = date("Y-m-d H:i:s"); //Дата выдачи займа;
        $loan_data["expiration_date"] = date("Y-m-d H:i:s", time() + $duration_agreement * 86400); //Дата выдачи займа;
        $loan_data["duration_actual"] = 0; //Действующий срок займа;
        $loan_data["interest_rate"] = $interest_rate; //Процентная ставка;
        $loan_data["fine_interest_rate"] = $fine_interest_rate; //Процентная ставка при просрочке;
        $loan_data["reward_sum"] = 0; //Сумма вознаграждения
        $loan_data["loan_status_id"] = 1; //Новая заявка
        $loan_data["loan_status_category_id"] = 1; //Заявка заполнена
        $loan_data["is_active"] = 1; //Действующий займ
        return $loan_data;
    }

    /**
     * Пересчет информации по дейтсвующему займу (каждый день)
     *
     * @param $sum
     * @param $duration_agreement
     * @return array
     */
    public static function recalculateLoanData($loan)
    {
        $loan_data = [];
        $config = AppHelper::getConfig();
        $minimum_period_use = $config["minimum_period_use"]; //Минимальный срок пользования займом для рассчета вознаграждения;

        $interest_rate = $loan->interest_rate; //процентная ставка;
        $reward_on_day = $loan->sum * $interest_rate; //ставка вознаграждения в день

        $fine_interest_rate = $loan->fine_interest_rate; //процентная ставка при просрочке;
        $fine_on_day = $loan->sum * $fine_interest_rate; //ставка вознаграждения в день при просрочке

        $is_overdue = strtotime($loan->expiration_date) < time(); //Является ли займ просроченным

        $loan_sum = $loan->sum;
        $issue_date = !empty($loan->extension_date) ? $loan->extension_date : $loan->issue_date; //дату выдачи считаем как дату продления в случае продления
        //$duration_agreement = $loan->duration_agreement;
        $duration_actual = ceil((time() - strtotime($issue_date)) / 86400); //действующий период займа

        $duration_actual = ($duration_actual > 0) ? $duration_actual - 1 : 0;

        if($duration_actual != 0 && $duration_actual < $minimum_period_use){
            $duration_actual = $minimum_period_use;
        }

        //$actual_period_delay = ceil((time() - strtotime($loan->expiration_date)) / 86400); //количество дней просрочки

        if($is_overdue){
           // $loan_data["fine_sum"] = $fine_on_day * $actual_period_delay; //Сумма просрочки
        }else{
           // $loan_data["reward_sum"] = $reward_on_day * ($duration_actual > $minimum_period_use ? $duration_actual : $minimum_period_use); //Сумма вознаграждения (срок пользования не менее 5 дней)
        }
        $loan_data["reward_sum"] = $reward_on_day * $duration_actual; //Сумма вознаграждения (срок пользования не менее 5 дней)

        $loan_data["duration_actual"] = $duration_actual;

       return $loan_data;
   }

    /**
     * Прогноз инфомрации по займу
     *
     * @param $sum
     * @param $duration_agreement
     * @return array
     */
    public static function forecastCalculateLoanData($loan, $expiration_time)
    {
        $loan_data = [];
        $config = AppHelper::getConfig();
        $minimum_period_use = $config["minimum_period_use"]; //Минимальный срок пользования займом для рассчета вознаграждения;
        $mrp = $config["mrp"]; //МРП;

        $interest_rate = $loan->interest_rate; //процентная ставка;
        $reward_on_day = $loan->sum * $interest_rate; //ставка вознаграждения в день

        $fine_interest_rate = $loan->fine_interest_rate; //процентная ставка при просрочке;
        $fine_on_day = $loan->sum * $fine_interest_rate; //ставка вознаграждения в день при просрочке

        $is_overdue = strtotime($loan->expiration_date) < $expiration_time; //Является ли займ просроченным

        $loan_sum = $loan->sum;
        $issue_date = !empty($loan->extension_date) ? $loan->extension_date : $loan->issue_date; //дату выдачи считаем как дату продления в случае продления
        //$duration_agreement = $loan->duration_agreement;
        $duration_actual = ceil(($expiration_time - strtotime($issue_date)) / 86400); //действующий период займа

        $actual_period_delay = ceil(($expiration_time - strtotime($loan->expiration_date)) / 86400); //количество дней просрочки
        $actual_period_delay = ($actual_period_delay < 0)? 0 : $actual_period_delay;

        $loan_data["is_overdue"] = $is_overdue; //Является ли займ просроченным
        $loan->fine_sum = $fine_on_day * $actual_period_delay; //Сумма просрочки
        $loan->reward_sum = $reward_on_day * ($duration_actual > $minimum_period_use ? $duration_actual : $minimum_period_use); //Сумма вознаграждения (срок пользования не менее 5 дней)

        //Начисляем МРП
        if($is_overdue){
            $loan->reward_sum += $mrp;
            $loan->status_text = LoanStatus::where("id", StatusHelper::LOAN_IS_OVERDUE)->first()->text;
        }else{
            $loan->status_text =  $loan->loanStatus->text;
        }

        $loan->duration_actual = $duration_actual;

       return $loan;
   }

    public function calculateLoanData($sum, $duration_agreement)
    {
        $loan_data = [];
        $config = AppHelper::getConfig();
        $interest_rate = $config["interest_rate"]; //процентная ставка;
        $minimum_period_use = $config["minimum_period_use"];

        $reward_on_day = $sum * $interest_rate; //ставка вознаграждения в день

        $loan_data["sum"] = $sum; //Сумма займа;
        $loan_data["duration_agreement"] = $duration_agreement; //Срок, на который оформляется займ;
        $loan_data["reward_sum"] = 0; //Сумма вознаграждения

        $loan_data["issue_date"] = date("Y-m-d H:i:s"); //Дата выдачи займа;
        $loan_data["expiration_date"] = date("Y-m-d H:i:s", time() + $duration_agreement * 86400); //Дата выдачи займа;

        return $loan_data;
    }

    /**
     * Формирует дату выдачи и дату истечения срока заявки относительно текущей даты
     *
     * @param $loan
     * @return mixed
     */
    public static function calculateDates($loan, $issue_date = null)
    {
        $loan_data["issue_date"] = $issue_date ?? date("Y-m-d H:i:s"); //Дата выдачи займа;
        $loan_data["expiration_date"] = date("Y-m-d H:i:s", strtotime($loan_data["issue_date"]) + $loan->duration_agreement * 86400); //Дата выдачи займа;

        return $loan_data;
    }
}