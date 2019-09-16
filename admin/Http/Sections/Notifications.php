<?php

namespace Admin\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
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
class Notifications extends Section implements Initializable
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
    protected $title = 'СМС уведомления';

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
        $display = AdminDisplay::table();

        $display->setColumns([
            AdminColumn::text('id', '#')->setWidth('30px'),
            AdminColumn::text('borrower_loan_id', 'Займ'),
            AdminColumn::text('message', 'Сообщение'),
            AdminColumn::text('enter', 'Переходы'),
            AdminColumn::text('status', 'Статус доставки'),
            AdminColumn::datetime('ref', 'Дата отправки')->setFormat('d.m.Y H:i'),
        ]);

        return $display;
    }

    // /**
    //  * @param int $id
    //  *
    //  * @return FormInterface
    //  */
    // public function onEdit($id)
    // {
    //     // return AdminForm::panel()->addBody([
    //     //     AdminFormElement::text('borrower_loan_id', 'Займ')->required(),
    //     //     AdminFormElement::text('message', 'Сообщение'),
    //     //     AdminFormElement::text('status', 'Статус доставки'),
    //     // ]);
    // }

    // /**
    //  * @return FormInterface
    //  */
    // public function onCreate()
    // {
    //     return $this->onEdit(null);
    // }

    // /**
    //  * @return FormInterface
    //  */
    // public function onDelete()
    // {
    //     return true;
    // }
}
