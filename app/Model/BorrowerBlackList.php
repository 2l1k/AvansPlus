<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BorrowerBlackList extends Model
{
    public $table = "borrower_black_list";
    public $timestamps = false;
}
