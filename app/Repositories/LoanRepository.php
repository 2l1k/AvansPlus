<?php

namespace App\Repositories;

use App\Model\BorrowerLoan;

class LoanRepository extends BaseRepository
{
   protected $model;

   public function __construct(BorrowerLoan $Loan)
   {
       $this->model = $Loan;
   }
}