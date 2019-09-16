<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    protected $table = 'borrowers';

    protected $guarded = [];

    public function borrowerLoans()
    {
        return $this->hasMany(BorrowerLoan::class);
    }

    public function borrowerLoanNotifications()
    {
        return $this->hasManyThrough(BorrowerLoanNotification::class, BorrowerLoan::class );
    }

    public function getFullNameAttribute()
    {
        return "{$this->lastname} {$this->firstname} {$this->fathername}";
    }

    public function getNameWithInitialsAttribute()
    {
        return $this->lastname . " " . mb_strtoupper(mb_substr($this->firstname, 0, 1)) . ". " . mb_strtoupper(mb_substr($this->fathername, 0, 1)) . ". ";
    }

    public function borrowerIdentificationCard()
    {
        return $this->hasOne(BorrowerIdentificationCard::class);
    }

    public function borrowerBankAccount()
    {
        return $this->hasOne(BorrowerBankAccounts::class);
    }

    public function borrowerEmployment()
    {
        return $this->hasOne(BorrowerEmployment::class);
    }

    public function borrowerAddress()
    {
        return $this->hasOne(BorrowerAddress::class);
    }

    public function borrowerAddressDocument()
    {
        return $this->hasOne(BorrowerAddressDocument::class, "borrower_id", "id");
    }

    public function borrowerIdCardDocument()
    {
        return $this->hasOne(BorrowerIdCardDocument::class, "borrower_id", "id");
    }

    public function borrowerPensionDocument()
    {
        return $this->hasOne(BorrowerPensionDocument::class, "borrower_id", "id");
    }

    public function unverifiedBorrowers($id_ = [])
    {
        if (empty($id_)) {
            return $this->borrowerRepository->all();
        } else {
            return $this->borrowerRepository->find($id_);
        }
    }

    /**
     * Забаненные
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithBanned($query)
    {
        return $query->where('is_banned', 1);
    }


    public static function boot()
    {
        parent::boot();

        static::created(function (Borrower $borrower) {


        });

        static::updated(function (Borrower $borrower) {

        });

        static::deleting(function (Borrower $borrower) {

            if (!empty($borrower->borrowerIdentificationCard)) {
                $borrower->borrowerIdentificationCard->delete();
            }
            if (!empty($borrower->borrowerBankAccount)) {
                $borrower->borrowerBankAccount->delete();
            }
            if (!empty($borrower->borrowerEmployment)) {
                $borrower->borrowerEmployment->delete();
            }
            if (!empty($borrower->borrowerAddress)) {
                $borrower->borrowerAddress->delete();
            }
            if (!empty($borrower->borrowerAddressDocument)) {
                $borrower->borrowerAddressDocument->delete();
            }
            if (!empty($borrower->borrowerIdCardDocument)) {
                $borrower->borrowerIdCardDocument->delete();
            }
            if (!empty($borrower->borrowerPensionDocument)) {
                $borrower->borrowerPensionDocument->delete();
            }
            if (!empty($borrower->borrowerLoans)) {
                $borrower->borrowerLoans()->delete();
            }
        });
    }

}
