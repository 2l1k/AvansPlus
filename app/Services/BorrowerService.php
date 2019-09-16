<?php

namespace App\Services;

use App\Repositories\BorrowerRepository;

class BorrowerService extends BaseService
{
   private $borrowerRepository;

   public function __construct(BorrowerRepository $borrowerRepository)
   {
       $this->borrowerRepository = $borrowerRepository;
   }

   public function borrower($borrower_id = false){
       $borrower_id = ($borrower_id !== false) ? $borrower_id : session("borrower_id");
       return $this->borrowerRepository->find($borrower_id);
   }


   public function borrowerByPhoneNumber($phone_number){
       return $this->borrowerRepository->findByPhoneNumber($phone_number);
   }
}