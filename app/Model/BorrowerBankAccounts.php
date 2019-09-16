<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BorrowerBankAccounts extends Model
{
    protected $table = 'borrower_bank_accounts';

    protected $guarded = [];

    protected $appends = [
        "bank_name_with_bik"
    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function getBankNameWithBikAttribute(){
        $result = '';
        if(!empty($this->number)){
            $bank_code = substr($this->number, 4, 3);
            $bank = Bank::withCode($bank_code)->first();
            if(!empty($bank)){
                $result = $bank->name . " ({$bank->bik})";
            }
        }
        return $result;
    }


}
