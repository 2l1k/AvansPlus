<?php

namespace Admin\Http\Sections;

use AdminForm;
use AdminFormElement;
use App\Helpers\StatusHelper;
use App\Model\BorrowerLoan;
use App\Model\DocumentCheckStatus;
use App\Model\IssuedAuthority;
use App\Model\SalaryObtainingMethod;
use App\Services\LoanService;
use Illuminate\Http\Request;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

use AdminColumn;
use AdminDisplay;

/**
 * Class Borrowers
 *
 * @property \App\Model\Borrower $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class ForecastBorrowerLoans extends Section
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
        return 'Прогноз по займам';
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $request = \Illuminate\Http\Request::capture();
        $table = $display = AdminDisplay::table();

        //Сортируем по ID убыванию
        $table->setApply(function ($query) {
            $query->where('loan_status_category_id', StatusHelper::CATEGORY_APPROVED)->orderBy('id', 'desc');
        });

        $table->setHtmlAttribute('class', 'forecast_table');
//        $table->setApply(function ($query) use ($request, $table) {
//            $query->orderBy('id', 'desc');
//        });
//
        $table->setTitle("Прогноз по займам на {$request->input("lf_days")} число");

        if (!empty($request->input("lf_days") && !empty($request->input("ids")))) {


            //Если передаём дату и ID шники займов, то рассчитываем прогноз

            $table->setApply(function ($query) use ($request, &$table) {
                $ids_ = explode(",", $request->input("ids"));
                $ids_ = (!is_array($ids_)) ? [$ids_] : $ids_;
                $query->whereIn('id', $ids_);
            });

        }


        $table->setColumns([
            AdminColumn::checkbox(),
            AdminColumn::text("id", "ID заявки"),
            AdminColumn::text("borrower.lastname", "Фамилия"),
            AdminColumn::text("borrower.firstname", "Имя"),
            AdminColumn::text("borrower.fathername", "Отчество"),
            AdminColumn::text("created_at", 'Дата заявки'),
            AdminColumn::custom("Период", function (BorrowerLoan $model) use ($request) {
                $lf_days = $request->input("lf_days");
                $model = LoanService::forecastCalculateLoanData($model, strtotime($lf_days));
                return "{$model->duration_actual} / {$model->duration_agreement}";
            }),
            AdminColumn::custom("Статус", function (BorrowerLoan $model) use ($request) {
                $lf_days = $request->input("lf_days");
                $model = LoanService::forecastCalculateLoanData($model, strtotime($lf_days));
                return "{$model->status_text}";
            }),
            AdminColumn::text('sum')->setLabel('Сумма займа')->setHtmlAttribute("sum", true),

            AdminColumn::custom("Сумма к возврату", function (BorrowerLoan $model) use ($request) {
                $lf_days = $request->input("lf_days");
                $model = LoanService::forecastCalculateLoanData($model, strtotime($lf_days));
                return $model->amount_maturity_without_paid_sum;
            })->setHtmlAttribute("amount_maturity", true),

            AdminColumn::custom("Чистая прибыль", function (BorrowerLoan $model) use ($request) {
                $lf_days = $request->input("lf_days");
                $model = LoanService::forecastCalculateLoanData($model, strtotime($lf_days));
                return $model->amount_maturity_without_paid_sum - $model->sum;
            })->setHtmlAttribute("net_profit", true),

            AdminColumn::custom("Временная точка", function (BorrowerLoan $model) use ($request) {
                $lf_days = $request->input("lf_days");
                return date("Y-m-d H:i", strtotime($lf_days));
            }),
        ]);

        $display->paginate(100);

        return $display;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return redirect()->to(url("/apadmin/borrower_loans/{$id}/edit"));
    }


}
