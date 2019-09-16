<?php

namespace App\Services;


use App\Model\LoanHistoryEvent;

class HistoryService extends BaseService
{

    private $model;

    public function __construct(LoanHistoryEvent $loanHistoryEvent)
    {
        $this->model = $loanHistoryEvent;
    }

    public function add($data)
    {
        return $this->model->create($data);
    }

}