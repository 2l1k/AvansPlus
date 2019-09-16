<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BorrowerLoanAgreementDocument extends Model
{

    protected $table = 'borrower_loan_agreement_documents';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function getFilePathsAttribute($value)
    {
        $v = json_decode($value);
        return (is_array($v) && (!empty($v) && !empty($v[0]))) ? $v :  null;
    }

    public function setFilePathsAttribute($values)
    {
        $values = is_array($values) && !empty($values) ? $values : explode(",", $values);
        $this->attributes['file_paths'] = json_encode($values);
    }

}
