<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BorrowerAddress extends Model
{

    public $timestamps = false;


    public function getFullAddressAttribute()
    {
        if (!empty($this->attributes['full_address'])) {
            return $this->attributes['full_address'];
        }elseif (!empty($this->raCity->name)) {
            return "г. {$this->raCity->name},  ул. {$this->ra_street_name},  дом {$this->ra_house_number} " . (!empty($this->ra_apartment_number) ? ", " . $this->ra_apartment_number : '');
        } else {
            return "";
        }
    }

    public function setFullAddressAttribute($value)
    {
        $this->attributes['full_address'] = $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function haCity()
    {
        return $this->hasOne(City::class, "id", "ha_city_id");
    }

    public function raCity()
    {
        return $this->hasOne(City::class, "id", "ra_city_id");
    }


}
