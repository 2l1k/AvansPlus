<?php

namespace App\Model;

use App\Model\Payment\QiwiTransaction;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    protected $guarded = [];

    /**
     * Номер заказа
     *
     * @return mixed
     */
    public function getOrderIdAttribute()
    {
        return $this->id;
    }

    /**
     * Заёмщик
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function borrower()
    {
        return $this->hasOne(Borrower::class, "id", "borrower_id");
    }

    public function borrowerLoan()
    {
        return $this->hasOne(BorrowerLoan::class, "id", "borrower_loan_id");
    }

    public function qiwiTransaction()
    {
        return $this->belongsTo(QiwiTransaction::class, "payment_order_id");
    }

    public function qiwiTransactions()
    {
        return $this->hasMany(QiwiTransaction::class, "payment_order_id");
    }

    public function scopeWithActive($query)
    {
        return $query->where('is_active', 1);
    }
}
