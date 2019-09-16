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
class BlackList_ extends Section implements Initializable
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
        $display = AdminDisplay::datatablesAsync();

        $display->setColumns([
            AdminColumn::text('id', '#')->setWidth('30px'),
            AdminColumn::text('IIN', 'ИИН'),
            AdminColumn::text('firstname', 'Имя'),
            AdminColumn::text('lastname', 'Фамилия'),
            AdminColumn::text('fathername', 'Отчество'),
        ])->setColumnFilters([
            null,
            AdminColumnFilter::text()->setPlaceholder('ИИН')->setOperator(FilterInterface::CONTAINS),
        ]);

        $display->getColumnFilters()->setPlacement('table.header');

        return $display;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return AdminForm::panel()->addBody([
            AdminFormElement::text('IIN', 'ИИН')->required(),
            AdminFormElement::text('firstname', 'Имя')->required(),
            AdminFormElement::text('lastname', 'Фамилия')->required(),
            AdminFormElement::text('fathername', 'Отчество')->required()
        ]);
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @return FormInterface
     */
    public function onDelete()
    {
        return true;
    }
}
