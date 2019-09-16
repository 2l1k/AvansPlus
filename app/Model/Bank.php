<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public $timestamps = false;

    /**
     * Search by bank code
     *
     * @param $query
     * @param $code
     * @return mixed
     */
    public function scopeWithCode($query, $code){
        return $query->where("code", $code);
    }
}
