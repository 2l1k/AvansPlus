<?php

namespace App\Model\Payment;

use App\Model\PaymentOrder;
use Illuminate\Database\Eloquent\Model;

class QiwiTransaction extends Model
{
    protected $guarded = [];

    /**
     * Заказ на оплату
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function paymentOrder()
    {
        return $this->hasOne(PaymentOrder::class, "id", "payment_order_id");
    }
}
