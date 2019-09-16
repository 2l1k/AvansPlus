<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BorrowerIdentificationCard extends Model
{
    protected $table = 'borrower_identification_cards';

    protected $guarded = [];

    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function verifiedDebtor()
    {
        return $this->hasOne(VerifiedDebtor::class, "IIN", "IIN");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function verifiedTaxpayer()
    {
        return $this->hasOne(VerifiedTaxpayer::class, "IIN", "IIN");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function verifiedRestricted()
    {
        return $this->hasOne(VerifiedRestricted::class, "IIN", "IIN");
    }

    /**
     * Кем выдано
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function issuedAuthority()
    {
        return $this->hasOne(IssuedAuthority::class, "id", "issued_authority_id");
    }



    public static function boot()
    {
        parent::boot();

        static::created(function( BorrowerIdentificationCard $borrowerIdentificationCard) {
            $iin = $borrowerIdentificationCard->IIN;

            //Добавляем IIN в список на верификацию должников по исполнительным производствам

            $verifiedDebtor = VerifiedDebtor::create(["IIN" => $iin]);
            $borrowerIdentificationCard->verifiedDebtor()->save($verifiedDebtor);

            //Добавляем IIN в список на верификацию среди налогоплательщиков
            $verifiedTaxpayer = VerifiedTaxpayer::create(["IIN" => $iin]);
            $borrowerIdentificationCard->verifiedTaxpayer()->save($verifiedTaxpayer);

            //Добавляем IIN в список на верификацию среди временно ограниченных на выезд
            $verifiedRestricted = VerifiedRestricted::create(["IIN" => $iin]);
            $borrowerIdentificationCard->verifiedRestricted()->save($verifiedRestricted);

        });

        static::updating(function( BorrowerIdentificationCard $borrowerIdentificationCard) {
            $iin = $borrowerIdentificationCard->IIN;

            if(empty( $borrowerIdentificationCard->verifiedDebtor)){
                $verifiedDebtor = VerifiedDebtor::create(["IIN" => $iin]);
                $borrowerIdentificationCard->verifiedDebtor()->save($verifiedDebtor);
            }
            if(empty( $borrowerIdentificationCard->verifiedTaxpayer)){
                $verifiedTaxpayer = VerifiedTaxpayer::create(["IIN" => $iin]);
                $borrowerIdentificationCard->verifiedTaxpayer()->save($verifiedTaxpayer);
            }
            if(empty( $borrowerIdentificationCard->verifiedRestricted)){
                $verifiedRestricted = VerifiedRestricted::create(["IIN" => $iin]);
                $borrowerIdentificationCard->verifiedRestricted()->save($verifiedRestricted);
            }
        });

        static::deleting(function( BorrowerIdentificationCard $borrowerIdentificationCard) {
            if (!empty($borrowerIdentificationCard->verifiedDebtor)) {
                $borrowerIdentificationCard->verifiedDebtor->delete();
            }
            if (!empty($borrowerIdentificationCard->verifiedTaxpayer)) {
                $borrowerIdentificationCard->verifiedTaxpayer->delete();
            }
            if (!empty($borrowerIdentificationCard->verifiedRestricted)) {
                $borrowerIdentificationCard->verifiedRestricted->delete();
            }
        });
    }

}
