<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoanStatusCategory extends Model
{
    protected $table = 'loan_status_categories';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanStatus()
    {
        return $this->hasMany(LoanStatus::class);
    }
}
