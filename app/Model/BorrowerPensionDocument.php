<?php

namespace App\Model;

use App\Helpers\StatusHelper;
use Illuminate\Database\Eloquent\Model;

class BorrowerPensionDocument extends Model
{
    protected $table = 'borrower_pension_documents';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function getImagesAttribute($value)
    {
        $v = json_decode($value);
        return (is_array($v) && (!empty($v) && !empty($v[0]))) ? $v :  null;
    }

    public function setImagesAttribute($values)
    {
        $values = is_array($values) && !empty($values) ? $values : explode(",", $values);
        $this->attributes['images'] = json_encode($values);
    }

    public static function boot()
    {
        parent::boot();

        static::updated(function (BorrowerPensionDocument $borrowerPensionDocument) {

            //Если статус проверки документа изменился
            $active_laon = $borrowerPensionDocument->borrower->borrowerLoans()->activeLoan()->first();
            if (!empty($active_laon)) {
                if ($borrowerPensionDocument->document_check_status_id == 3 && $active_laon->loan_status_id != StatusHelper::LOADING_DOCUMENTS){
                    $active_laon->changeStatus(StatusHelper::LOADING_DOCUMENTS);
                }
            }
        });
    }

}
