<?php

namespace App\Presenters;

class BorrowerPresenter extends Presenter
{
    public function fullName()
    {
        return trim($this->model->full_name . ' ' . $this->model->last_name);
    }

    public function createdAt()
    {
        return $this->model->created_at->format('n/j/Y');
    }

    public function isActive()
    {
        return (bool) $this->model->is_active;
    }

}