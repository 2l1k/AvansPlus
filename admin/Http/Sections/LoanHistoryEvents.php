<?php

namespace Admin\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminSection;
use App\Model\Contact;
use App\Model\LoanHistoryEvent;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Navigation\Page;
use SleepingOwl\Admin\Section;

/**
 * Class Countries
 *
 * @property \App\Model\Country $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class LoanHistoryEvents extends Section implements Initializable
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = true;

    /**
     * @var string
     */
    protected $title = 'Системные уведомления';

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
        // $this->addToNavigation()->setIcon('fa fa-globe');
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::table();


        $request = \Illuminate\Http\Request::capture();


        $display->setApply(function ($query) use ($request) {
            $history_key = $request->input('history_key');
            $is_read = $request->input('is_read');

            //Если задан ключ событий, фильтруем
            if (!empty($history_key)) {
                $history_keys = explode(",", $history_key);
                $history_keys = is_array($history_keys) ? $history_keys : [$history_keys];
                foreach ($history_keys as $history_key) {
                    $query->where('history_key', "like", "%{$history_key}%");
                }
            }

            //Прочитанные || !
            if (!empty($is_read)) {
                $query->where('is_read', $is_read);
            }

            $query->orderBy('id', 'desc');
        });

        $display->setColumns([
            AdminColumn::text('created_at', 'Время'),
            AdminColumn::custom("Займ", function (LoanHistoryEvent $model) {
                if(!empty($model->borrowerLoan)){
                    return "<a href='". $model->borrowerLoan->admin_edit_url ."'>№{$model->borrowerLoan->loan_id} - Перейти к займу</a>";
                }
            }),
            AdminColumn::text('text', 'Уведомление'),
            AdminColumn::custom("Статус", function (LoanHistoryEvent $model) {
                $model->update([
                    "is_read" => 1
                ]);
                return $model->read_status;
            }),
        ]);

        return $display;
    }


}
