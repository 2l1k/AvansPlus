<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BorrowerLoanNotification extends Model
{

    protected $table = 'borrower_loan_notifications';

    protected $guarded = [];

    public $timestamps = false;



    public function scopeNotViewed($query){
        return $query->whereNull("is_viewed");
    }

}
