<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BorrowerEmployment extends Model
{
    protected $table = 'borrower_employments';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function borrower()
    {
        return $this->hasOne(Borrower::class);
    }


    /**
     * Способы получения зарплаты
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function salaryObtainingMethod()
    {
        return $this->hasOne(SalaryObtainingMethod::class, "id", "salary_obtaining_method_id");
    }


}
