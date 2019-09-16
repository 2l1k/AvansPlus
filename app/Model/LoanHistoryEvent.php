<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoanHistoryEvent extends Model
{

    protected $guarded = [];

    public function getReadStatusAttribute(){
        return ($this->attributes["is_read"] == 1) ? "Просмотрено": "Новое уведомление";
    }

    /**
     * Займ
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function borrowerLoan()
    {
        return $this->hasOne(BorrowerLoan::class, "id", "borrower_loan_id");
    }

    /**
     * Системные уведомления
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithSystem($query)
    {
        return $query->where('history_key',"like", "%system%");
    }

    /**
     * Не прочитанные
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithUnread($query)
    {
        return $query->where('is_read', "<>", 1);
    }

}
