<?php

namespace Admin\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Model\Borrower;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

/**
 * Class Countries
 *
 * @property \App\Model\Country $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class BlackList extends Section implements Initializable
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Черный список';

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display_table = AdminDisplay::table()->setModelClass(Borrower::class);

        //Сортируем по ID убыванию
        $display_table->setApply(function ($query) {
            $query->orderBy('unlock_date', 'desc');
            $query->where('is_banned', 1);
        });

        $display_table->setColumns([
            AdminColumn::text('firstname', 'Имя'),
            AdminColumn::text('lastname', 'Фамилия'),
            AdminColumn::text('fathername', 'Отчество'),
            AdminColumn::text('borrowerIdentificationCard.IIN', 'ИИН'),
            AdminColumn::text('unlock_date', 'Дата окончания'),
        ]);


        return $display_table;
    }


    /**
     * @param int $id
     *
     * @return FormInterface
     */

    public function onEdit($id)
    {
        return redirect()->to(url("/admin/borrowers/{$id}/edit"));
    }

}
