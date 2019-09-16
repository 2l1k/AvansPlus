<?php

namespace App\Model;

use App\Helpers\LoanHelper;
use App\Helpers\MailHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\StatusHelper;
use App\Presenters\LoanPresenter;
use App\Services\HistoryService;
use App\Services\LoanService;
use App\Traits\HasAdminSection;


class BorrowerLoan extends BaseModel
{
    use HasAdminSection;

    protected $table = 'borrower_loans';

    protected $guarded = [];

    protected $hidden = [
        "loan_status_category_id"
    ];

    /**
     * Переопределяем и округляем сумму займа
     *
     * @return mixed
     */
    public function getSumAttribute()
    {
        return ceil($this->attributes['sum']);
    }

    /**
     * Переопределяем и округляем сумму вознаграждения
     *
     * @return mixed
     */
    public function getRewardSumAttribute()
    {
        return ceil($this->attributes['reward_sum']);
    }

    /**
     * Сумма к погашению
     *
     * @return mixed
     */
    public function getAmountMaturityAttribute()
    {
        $loanPresenter = new LoanPresenter($this);
        $amount_maturity = $loanPresenter->amountMaturity();
        return $amount_maturity;
    }

    /**
     * Сумма к погашению
     *
     * @return mixed
     */
    public function getAmountMaturityWithoutPaidSumAttribute()
    {
        $loanPresenter = new LoanPresenter($this);
        $amount_maturity = $loanPresenter->amountMaturityWithoutPaidSum();
        return $amount_maturity;
    }

    /**
     * Сумма вознаграждения (Сумма вознаграждения + сумма просрочки)
     *
     * @return mixed
     */
    public function getAllRewardSumAttribute()
    {
        return $this->reward_sum + $this->fine_sum;
    }

    /**
     * ID займа
     *
     * @return mixed
     */
    public function getLoanIdAttribute()
    {
        return $this->id;
    }

    public function borrower()
    {
        return $this->hasOne(Borrower::class, "id", "borrower_id");
    }

    public function borrowerLoanAgreementDocument()
    {
        return $this->hasOne(BorrowerLoanAgreementDocument::class);
    }

    public function loanStatus()
    {
        return $this->hasOne(LoanStatus::class, "id", "loan_status_id");
    }

    public function paymentOrders()
    {
        return $this->hasMany(PaymentOrder::class, "borrower_loan_id", "id");
    }

    public function loanHistoryEvent()
    {
        return $this->hasMany(LoanHistoryEvent::class, "borrower_loan_id", "id");
    }

    /**
     * Комментарии по статусам обзвона
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dialingComments()
    {
        return $this->hasMany(BorrowerLoanDialingComment::class, "borrower_loan_id", "id");
    }

    /**
     * Все займы по заёмщику
     *
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeLoansByBorrowerId($query, $id)
    {
        return $query->where('borrower_id', $id);
    }

    /**
     * Активные займы
     *
     * @param $query
     * @return mixed
     */
    public function scopeActiveLoan($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Новые займы
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithNew($query)
    {
        return $query->whereIn('loan_status_id', [
            StatusHelper::APP_FILLED_BY_CLIENT
        ]);
    }

    /**
     * Только одобренные
     *
     * @param $query
     * @return mixed
     */
    public function scopeApproved($query)
    {
        return $query->where('loan_status_category_id', StatusHelper::CATEGORY_APPROVED);
    }

    /**
     * Только с возможностью погашения или взноса
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithPossiblePayment($query)
    {
        return $query->where('loan_status_category_id', StatusHelper::CATEGORY_APPROVED);
    }

    public function scopeChangeStatus($query, $loan_status_id)
    {
        $borrower_loan = BorrowerLoan::find($this->id);
        $loanStatus = LoanStatus::find($loan_status_id);

        if ($loanStatus) {
            $loan_status_category_id = $loanStatus->loan_status_category_id;
            $borrower_loan->fill([
                'loan_status_id' => $loan_status_id,
                'loan_status_category_id' => $loan_status_category_id,
            ]);
            $borrower_loan->save();

        }
    }


    public static function boot()
    {
        parent::boot();

        static::created(function (BorrowerLoan $borrower_loan) {
            //Добавляем системное уведомление
            $historyService = new HistoryService(new LoanHistoryEvent());
            $historyService->add([
                "borrower_loan_id" => $borrower_loan->id,
                "text" => "Добавлен новый займ: '{$borrower_loan->loanStatus->text}'",
                "history_key" => "system"
            ]);

            if (empty($borrower_loan->borrowerLoanAgreementDocument)) {
                $borrowerLoanAgreementDocument = BorrowerLoanAgreementDocument::create(["document_check_status_id" => 1]);
                $borrower_loan->borrowerLoanAgreementDocument()->save($borrowerLoanAgreementDocument);
            }

        });
        static::updating(function (BorrowerLoan $new_borrower_loan) {
            $historyService = new HistoryService(new LoanHistoryEvent());
            $borrower_loan = BorrowerLoan::find($new_borrower_loan->id);

            //Устанавливаем дату проддения в зависимости от суммы поашенной клиентом
            if (!empty($new_borrower_loan->customer_paid_sum)) {
                $reward_on_day = $new_borrower_loan->sum * $new_borrower_loan->interest_rate; //ставка вознаграждения в день
                $days_extended = $new_borrower_loan->customer_paid_sum / $reward_on_day;

                //Пересчитываем сумму вознаграждения
                $reward_sum = 0;
                if($new_borrower_loan->customer_paid_sum <= $new_borrower_loan->reward_sum){
                    $new_borrower_loan->reward_sum -= $new_borrower_loan->customer_paid_sum;
                }

                 //хак, удаляющий ошибочные даты продления
                if(!empty($new_borrower_loan->extension_date) && strtotime($new_borrower_loan->extension_date) < strtotime("2016-01-01 00:00:00")){
                    $new_borrower_loan->extension_date = null;
                }

                if(empty($new_borrower_loan->extension_date)){
                    $extension_date =  $new_borrower_loan->issue_date;
                }else{
                    $extension_date = $new_borrower_loan->extension_date;
                }

                //Если дата продления меньше текущей даты, то можем применить продление
                if(strtotime($extension_date) < time()){
                    $new_borrower_loan->extension_date =  date("Y-m-d H:i:s", strtotime($extension_date) + $days_extended * 86400);
                    //Добавляем системное уведомление
                    $historyService->add([
                        "borrower_loan_id" => $borrower_loan->id,
                        "text" => "Внесена сумма: {$new_borrower_loan->customer_paid_sum}. Изменился срок продления с {$borrower_loan->extension_date} на {$new_borrower_loan->extension_date}",
                        "history_key" => "system extension",
                    ]);
                }
            }
            unset($new_borrower_loan->customer_paid_sum);

            //Устанавливаем дату окончания срока в зависимости от даты выдачи (или продления) и срока займа
            if (!empty($new_borrower_loan->extension_date)) {
                $extension_date = !empty($new_borrower_loan->extension_date) ? $new_borrower_loan->extension_date : null;
                $dates = LoanService::calculateDates($new_borrower_loan, $extension_date);
                $new_borrower_loan->extension_date = $dates["issue_date"];
            } else {
                $issue_date = !empty($new_borrower_loan->issue_date) ? $new_borrower_loan->issue_date : null;
                $dates = LoanService::calculateDates($new_borrower_loan, $issue_date);
                $new_borrower_loan->issue_date = $dates["issue_date"];
            }

            $new_borrower_loan->expiration_date = $dates["expiration_date"];


            //Если статус займа изменился
            if ($new_borrower_loan->loan_status_id != $borrower_loan->loan_status_id) {
                //Если статус займа - выдано, то меняем дату выдачи и дату истечения срока на актуальные значения
                if ($borrower_loan->loan_status_id == StatusHelper::TO_ISSUE
                    && $new_borrower_loan->loan_status_id == StatusHelper::LOAN_ISSUED) {
                    $issue_date = !empty($new_borrower_loan->issue_date) ? $new_borrower_loan->issue_date : null;
                    $dates = LoanService::calculateDates($new_borrower_loan, $issue_date);
                    $new_borrower_loan->issue_date = $dates["issue_date"];
                    $new_borrower_loan->expiration_date = $dates["expiration_date"];
                    //Отправляем уведомление на почту
                    NotificationHelper::sendNotification("loan_issued", [
                        "to" => $new_borrower_loan->borrower->email,
                        "loan" => $new_borrower_loan,
                    ]);
                }

                //ОТПРАВКА УВЕДОМЛЕНИЙ

                //Если статус займа - новая заявка
                if ($new_borrower_loan->loan_status_id == StatusHelper::APP_FILLED_BY_CLIENT) {
                    NotificationHelper::sendNotification("application_accepted", [
                        "to" => $new_borrower_loan->borrower->email,
                        "loan" => $new_borrower_loan,
                    ]);
                }
                //Если статус займа - загрузка документов
                if ($new_borrower_loan->loan_status_id == StatusHelper::LOADING_DOCUMENTS) {
                    NotificationHelper::sendNotification("loading_documents", [
                        "to" => $new_borrower_loan->borrower->email,
                        "loan" => $new_borrower_loan,
                    ]);
                }

                //Если статус займа - закрыто или отказано
                if (in_array($new_borrower_loan->loan_status_id, [
                    StatusHelper::CLOSED,
                    StatusHelper::PAID_BY_NOTARIAL_SIGNATURE,
                    StatusHelper::CLOSED_BY_COURT_ORDER,
                    StatusHelper::REFUSED
                ])) {
                    if (StatusHelper::CLOSED == $new_borrower_loan->loan_status_id) {
                        $new_borrower_loan->closing_date = date("Y-m-d H:i:s");
                    }

                    $new_borrower_loan->is_active = 0;
                    //Если отказано, то отправляем уведомление на email и пользователю в личный кабинет
                    if ($new_borrower_loan->loan_status_id == StatusHelper::REFUSED) {
                        BorrowerLoanNotification::create([
                            "borrower_loan_id" => $new_borrower_loan->id,
                            "type" => "loan_refused",
                            "message" => "Извините, вам отказано в займе",
                            "is_viewed" => null,
                        ]);
                        NotificationHelper::sendNotification("refused", [
                            "to" => $new_borrower_loan->borrower->email,
                            "loan" => $new_borrower_loan,
                        ]);
                    }
                } else {
                    $new_borrower_loan->is_active = 1;
                }

                //Добавляем системное уведомление
                $historyService = new HistoryService(new LoanHistoryEvent());
                $historyService->add([
                    "borrower_loan_id" => $borrower_loan->id,
                    "text" => "Изменился статус на '{$new_borrower_loan->loanStatus->text}'",
                    "history_key" => "system",
                ]);
            }
        });

        static::deleting(function (BorrowerLoan $borrower_loan) {
            if (!empty($borrower_loan->borrowerBankAccount)) {
                $borrower_loan->borrowerBankAccount->delete();
            }
            if (!empty($borrower_loan->borrower)) {
                //$borrower_loan->borrower->delete(); // Удаляем заёмщика
            }
            $borrower_loan->dialingComments()->delete(); //Удаляем комментарии к статусам обзвона
        });
    }
}
