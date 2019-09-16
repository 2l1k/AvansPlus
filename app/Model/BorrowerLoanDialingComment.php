<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BorrowerLoanDialingComment extends Model
{

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrowerLoan()
    {
        return $this->hasOne(BorrowerLoan::class);
    }

    /**
     * Статус обзвона
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dialingStatus()
    {
        return $this->hasOne(DialingStatus::class, "id", "dialing_status_id");
    }
}
