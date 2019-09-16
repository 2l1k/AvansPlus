<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VerifiedRestricted extends Model
{

    public $table = "verified_restricted";

    protected $guarded = [];
    public $timestamps = false;

}
