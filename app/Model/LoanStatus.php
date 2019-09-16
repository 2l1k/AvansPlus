<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoanStatus extends Model
{
    protected $table = 'loan_statuses';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanStatusCategory()
    {
        return $this->hasOne(LoanStatusCategory::class, "id", "loan_status_category_id");
    }
}
