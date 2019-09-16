<?php

namespace App\Helpers;


use App\Model\Borrower;

class SessionHelper
{

    public static function borrower()
    {
        if(!empty(session("borrower_id"))){
            $borrower = Borrower::find(session("borrower_id"));
            return $borrower;
        }else{
            return null;
        }
    }
}
