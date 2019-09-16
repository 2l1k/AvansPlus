<?php

namespace App\Repositories;

use App\Model\Borrower;

class BorrowerRepository extends BaseRepository
{
   protected $model;

   public function __construct(Borrower $borrower)
   {
       $this->model = $borrower;
   }

    public function findByPhoneNumber($phone_number)
    {
        return $this->model->where('phone_number', $phone_number)->first();
    }

    public function findByIIN($IIN)
    {
        return $this->model->with('borrowerIdentificationCard')->whereHas('borrowerIdentificationCard', function($q) use ($IIN){
            $q->where('IIN', $IIN);
        })->first();
    }

}