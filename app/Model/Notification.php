<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    protected $table = 'borrower_sms_notifications';
    protected $fillable = [
        'borrower_loan_id', 
        'borrower_id', 
        'message', 
        'status', 
        'enter',
        'ref'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Все SMS уведомления по заёмщику
     *
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeSmsByBorrowerId($query, $id)
    {
        return $query->where('borrower_id', $id);
    }
}