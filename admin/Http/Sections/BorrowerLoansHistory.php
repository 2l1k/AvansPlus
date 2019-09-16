<?php

namespace Admin\Http\Sections;

use AdminColumnFilter;
use AdminSection;
use App\Helpers\FileHelper;
use App\Model\Borrower;
use App\Model\BorrowerLoan;
use App\Model\City;
use App\Model\DialingStatus;
use App\Model\DocumentCheckStatus;
use App\Model\IssuedAuthority;
use App\Model\LoanStatus;
use App\Model\LoanStatusCategory;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Form\FormElements;
use SleepingOwl\Admin\Section;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;

/**
 * Class BorrowerLoans
 *
 * @property \App\Model\BorrowerLoan $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class BorrowerLoansHistory extends Section
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
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    public function getTitle()
    {
        return 'Заявки';
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display_table = AdminDisplay::table()->setModelClass(BorrowerLoan::class)->with('borrower', 'loanStatus');

        //Сортируем по ID убыванию
        $display_table->setApply(function ($query)
        {
            $query->orderBy('id', 'desc');
        });

        $display_table->setColumns([
                AdminColumn::checkbox(),
                AdminColumn::text("id", "ID заявки"),
                AdminColumn::text('sum')->setLabel('Сумма займа'),
                AdminColumn::custom("Сумма к возврату", function(BorrowerLoan $model) {
                    return $model->sum + $model->all_reward_sum ;
                }),
                AdminColumn::text("created_at", 'Дата заявки'),
                AdminColumn::text("loanStatus.text", 'Статус'),
            ]);


        $display_table->paginate(30);

        return $display_table;
    }


}
