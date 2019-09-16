<?php

namespace App\Traits;

use SleepingOwl\Admin\Navigation\Page;

trait HasAdminSection
{

    /**
     * Экшены для админки
     *
     * @return mixed
     */
    public function getAdminEditUrlAttribute()
    {
        return (new Page(__CLASS__))->getUrl() . "/{$this->id}/edit";
    }

}