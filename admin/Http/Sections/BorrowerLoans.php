<?php

namespace Admin\Http\Sections;

use AdminColumnFilter;
use AdminSection;
use App\Helpers\FileHelper;
use App\Helpers\StatusHelper;
use App\Model\Borrower;
use App\Model\BorrowerLoan;
use App\Model\BorrowerLoanAgreementDocument;
use App\Model\BorrowerLoanDialingComment;
use App\Model\City;
use App\Model\DialingStatus;
use App\Model\DocumentCheckStatus;
use App\Model\IssuedAuthority;
use App\Model\LoansByBorrower;
use App\Model\LoanStatus;
use App\Model\LoanStatusCategory;
use App\Model\SalaryObtainingMethod;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Delete;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Form\FormElements;
use SleepingOwl\Admin\Navigation\Page;
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
class BorrowerLoans extends Section
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
        $tabs          = AdminDisplay::tabbed();
        $display_table = AdminDisplay::datatablesAsync()->setModelClass(BorrowerLoan::class)->with('borrower', 'loanStatus');

        //Сортируем по ID убыванию
        $display_table->setApply(function ($query) {
            $query->orderBy('updated_at', 'desc');
        });

        $display_table->setHtmlAttribute('class', 'table-primary loan_list_table')
            ->setColumns([
                AdminColumn::checkbox(),
                AdminColumn::text("id", "ID заявки"),
                AdminColumn::text("borrower.lastname", "Фамилия"),
                AdminColumn::text("borrower.firstname", "Имя"),
                AdminColumn::text("borrower.fathername", "Отчество"),
                AdminColumn::text("created_at", 'Дата заявки'),
                AdminColumn::custom("Период", function (BorrowerLoan $model) {
                    return "{$model->duration_actual} / {$model->duration_agreement}";
                }),
                AdminColumn::text("loanStatus.text", 'Статус')->setWidth("250px"),
                AdminColumn::text('sum')->setLabel('Сумма займа'),
                AdminColumn::custom("Сумма к возврату", function (BorrowerLoan $model) {
                    return $model->amount_maturity;
                }),
            ])->setColumnFilters([
                null,
                null,
                AdminColumnFilter::text()->setPlaceholder('Фамилия')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Имя')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Отчество')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::range()->setFrom(
                    AdminColumnFilter::date()->setFormat('Y-m-d')->setPlaceholder('От')
                )->setTo(
                    AdminColumnFilter::date()->setFormat('Y-m-d')->setPlaceholder('До')
                ),
                null,
                AdminColumnFilter::select(new LoanStatus, 'Статус')->setSortable(false)->setDisplay('text')->setColumnName('loanStatus.id')->setPlaceholder('Выберите статус'),
                null
            ]);

        $display_table->getColumnFilters()->setPlacement('table.header');
        $display_table->addStyle('cystom-style', asset('apadmin/custom.css'));
        $display_table->paginate(30);
        $section_actions_data                         = [];
        $loan_forecast_action_page                    = new Page(\App\Model\ForecastBorrowerLoan::class);
        $section_actions_data["loan_forecast_action"] = $loan_forecast_action_page->getUrl();//Action страницы прогнозирования займов

        $tabs->appendTab(
            new  FormElements([
                $display_table,
                view("admin::loan_list_header", $section_actions_data),
            ])
            , 'Список заявок'

        );

        return $tabs;
    }


    /**
     * @param int $id
     *
     * @return FormInterface
     */

    public function onEdit($id)
    {
        $form = AdminForm::panel();

        $form->addElement($form->getButtons());

        $borrower_loan = BorrowerLoan::find($id);

        $borrower_loans = null;
        if ($borrower_loan) {
            $borrower = Borrower::find($borrower_loan->borrower_id);

            //Все займы по заёмщику
            $borrower_loans = AdminSection::getModel(LoansByBorrower::class)->fireDisplay();
            $borrower_loans->getScopes()->push(['loansByBorrowerId', $borrower->id]);
        }

        $form->addElement(AdminDisplay::tabbed([
            'Статус займа' => new \SleepingOwl\Admin\Form\FormElements([
                AdminFormElement::columns()
                    ->addColumn(function () use ($borrower_loan) {
                        return [
                            AdminFormElement::select('loan_status_category_id', 'Статус займа', LoanStatusCategory::class)
                                ->setDisplay('text')
                                ->setLoadOptionsQueryPreparer(function ($item, $query) use ($borrower_loan) {

                                   /* if (!empty($borrower_loan)) {
                                        //Настраиваем отображение статусов, чтобы нельзя было случайно закрыть или одобрить
                                        //Если займ в статусе "Новая заявка" и сотсоянии "Заявка подтверждена", открываем статусы по одобрению и закрытию
                                        if ($borrower_loan->loan_status_category_id == StatusHelper::CATEGORY_NEW && $borrower_loan->loan_status_id != StatusHelper::APPLICATION_CONFIRMED) {
                                            $query->where('id', $borrower_loan->loan_status_category_id);
                                        }

                                        //Если займ в статусе одобрен и в состояниях отличных от "на выдачу", разрешаем закрытие займа
                                        if ($borrower_loan->loan_status_category_id == StatusHelper::CATEGORY_APPROVED && in_array($borrower_loan->loan_status_id, [StatusHelper::LOAN_ISSUED, StatusHelper::LOAN_PAID, StatusHelper::LOAN_EXTENDED, StatusHelper::LOAN_IS_OVERDUE, StatusHelper::NOTARIZED])) {
                                            $query->orWhere('id', StatusHelper::CATEGORY_APPROVED);
                                            $query->orWhere('id', StatusHelper::CATEGORY_CLOSED);
                                        } elseif ($borrower_loan->loan_status_category_id == StatusHelper::CATEGORY_CLOSED) {

                                        } else {
                                            $query->where('id', "<>", StatusHelper::CATEGORY_CLOSED);
                                        }
                                        $query->orWhere('id', StatusHelper::CATEGORY_REJECTED);
                                    }*/
                                    return $query;
                                })
                                //->setValueSkipped(true)
                                ->setSortable(false)
                                ->required('Выберите значение')
                        ];
                    })->addColumn(function () use ($borrower_loan) {
                        return [
                            AdminFormElement::dependentselect('loan_status_id', 'Состояние')
                                ->setModelForOptions(LoanStatus::class, 'text')
                                ->setDataDepends(['loan_status_category_id'])
                                ->setSortable(false)
                                ->setLoadOptionsQueryPreparer(function ($item, $query) use ($borrower_loan) {
                                    $query->where('loan_status_category_id', $item->getDependValue('loan_status_category_id'));
                                    return $query;
                                })->setDisplay('text')
                                ->required('Выберите значение'),
                            AdminFormElement::checkbox('is_counteroffer', 'Встречное предложение сформировано')
                                ->setHtmlAttribute("class", "pull-right")
                                ->required('Подтвердите действие')
                        ];
                    })->addColumn(function () use ($borrower_loan) {
                        return [
                            AdminFormElement::date('borrower.unlock_date', 'Бан-лист')
                                ->setDefaultValue(0),
                            AdminFormElement::checkbox('borrower.is_banned', 'Добавить заёмщика в бан')
                                ->setHtmlAttribute("class", "pull-right")
                        ];
                    })
            ])
        ])
        );

        $tabs = AdminDisplay::tabbed();

        $inputs_for_columns = AdminFormElement::columns();

        // Основная информация о займе
        $inputs_for_columns->addColumn(function () use ($borrower_loan) {
            return [
                AdminFormElement::number('sum', 'Сумма займа')->required('Введите сумму'),
                //->setHtmlAttribute("readonly", "false")
                AdminFormElement::number('duration_agreement', 'Срок, на который оформляется займ')
                    ->required('Введите сумму'),
                AdminFormElement::number('counteroffer_sum', 'Сумма встречного предложения')
                    ->required('Введите сумму'),
                AdminFormElement::number('counteroffer_duration_agreement', 'Срок встречного предложения')
                    ->setDefaultValue(0)
                    ->mutateValue(function ($value) {
                        return empty($value) ? 0 : (int)$value;
                    }),
                AdminFormElement::date('issue_date', 'Дата выдачи займа')
                    //->setHtmlAttribute("readonly", "true")
                    ->setDefaultValue(0),
                AdminFormElement::columns()->addColumn(function () use ($borrower_loan) {
                    return [
                        AdminFormElement::date('extension_date', 'Последнее продление займа')
                            ->setDefaultValue(0),
                    ];
                })->addColumn(function () use ($borrower_loan) {
                    $extension_text = (!empty($borrower_loan->extension_date)) ? " <span style='color: green;'>Продлён до " . date("d.m.Y", strtotime($borrower_loan->extension_date) + $borrower_loan->duration_agreement * 86400) . "</span>" : "";
                    return [
                        $extension_text
                    ];
                }),
                AdminFormElement::date('expiration_date', 'Дата истечения срока договора')
                    ->setHtmlAttribute("readonly", "true")
                    ->setDefaultValue(0),
            ];
        })->addColumn(function () {
            return [
                AdminFormElement::columns()
                    ->addColumn(function () {
                        return [
                            AdminFormElement::selectajax('borrower_id', 'Заёмщик')
                                ->setModelForOptions(\App\Model\Borrower::class)
                                ->setDisplay("phone_number")
                                ->setHtmlAttribute("readonly", "true")
                                ->required('Выюерите значение')
                        ];
                    })->addColumn(function () {
                        return [
                            AdminFormElement::custom()->setDisplay(function (\Illuminate\Database\Eloquent\Model $borrowerLoan) {
                                if ($borrowerLoan->borrower) {
                                    return "<label class='control-label'>&nbsp;</label><div><a href='/apadmin/borrowers/{$borrowerLoan->borrower_id}/edit' target='_blank'>{$borrowerLoan->borrower->full_name}</a></div>";
                                }
                            }),
                        ];
                    }),
                AdminFormElement::select('dialing_status_id', 'Последний статус обзвона', DialingStatus::class)->setDisplay('text'),
                //Комментарии к статусам обзвона
                AdminFormElement::custom()->setDisplay(function (\Illuminate\Database\Eloquent\Model $borrowerLoan) {
                    return AdminFormElement::columns()
                        ->addColumn(function () use ($borrowerLoan) {
                            return [
                                view("admin::dialing_comments_table", [
                                    "dialing_comments" => $borrowerLoan->dialingComments
                                ]),
                                AdminFormElement::textarea('dialing_comment', 'Примечание'),
                            ];
                        });
                })
                    ->setCallback(function (\Illuminate\Database\Eloquent\Model $newBorrowerLoan) {
                        $borrowerLoan = BorrowerLoan::find($newBorrowerLoan->id);

                        if (!empty($borrowerLoan)) {
                            $request         = \Illuminate\Http\Request::capture();
                            $dialing_comment = $request->input('dialing_comment');
                            //Если заполнен комментарий или изменен статус обзвона
                            if (!empty($dialing_comment) || $newBorrowerLoan->dialing_status_id != $borrowerLoan->dialing_status_id) {
                                BorrowerLoanDialingComment::create([
                                    "borrower_loan_id"  => $newBorrowerLoan->id,
                                    "comment"           => $dialing_comment,
                                    "dialing_status_id" => $newBorrowerLoan->dialing_status_id,
                                ]);
                            }
                        }
                    })
            ];
        });

        if (empty($borrower_loan)) {
            $tabs->appendTab(new \SleepingOwl\Admin\Form\FormElements([
                $inputs_for_columns
            ]), 'Основная информация');
        } else {
            $tabs->appendTab(new \SleepingOwl\Admin\Form\FormElements([
                $inputs_for_columns,
                "<h3>Анкета</h3>",
                AdminFormElement::columns()
                    ->addColumn(function () {
                        return [
                            AdminFormElement::text('borrower.lastname', 'Фамилия'),
                            AdminFormElement::text('borrower.firstname', 'Имя'),
                            AdminFormElement::text('borrower.fathername', 'Отчество'),
                            AdminFormElement::text('borrower.email', 'Email'),
                            AdminFormElement::text('borrower.phone_number', 'Телефон'),
                            AdminFormElement::date('borrower.DOB', 'Дата рождения')->setFormat('m-d-Y')->setHtmlAttribute("data-date", true),

                        ];
                    })->addColumn(function () {
                        return [
                            AdminFormElement::text('borrower.borrowerIdentificationCard.IIN', 'ИИН'),
                            AdminFormElement::date('borrower.borrowerIdentificationCard.issue_date', 'Дата выдачи')->setHtmlAttribute("data-date", true),
                            AdminFormElement::date('borrower.borrowerIdentificationCard.expiration_date', 'Дата окончания')->setHtmlAttribute("data-date", true),
                            AdminFormElement::number('borrower.borrowerIdentificationCard.number', 'Номер удостоверения'),
                            AdminFormElement::select('borrower.borrowerIdentificationCard.issued_authority_id', 'Кем выдано', IssuedAuthority::class)
                                ->setDisplay("text"),
                        ];
                    })->addColumn(function () {
                        return [
                            AdminFormElement::text('borrower.borrowerEmployment.work_place', 'Место работы'),
                            AdminFormElement::text('borrower.borrowerEmployment.salary', 'Размер заработной платы'),
                            AdminFormElement::text('borrower.borrowerEmployment.working_position', 'Должность'),
                            AdminFormElement::select('borrower.borrowerEmployment.salary_obtaining_method_id', 'Способ получения зарплаты', SalaryObtainingMethod::class)
                                ->setDisplay("text"),
                        ];
                    }),
                "<hr>",
                AdminFormElement::columns()
                    ->addColumn(function () use ($borrower_loan) {
                        if ($borrower_loan) {
                            $borrowerIdentificationCard = $borrower_loan->borrower->borrowerIdentificationCard;
                            $verified_debtor            = $borrowerIdentificationCard->verifiedDebtor;
                            $verified_taxpayer          = $borrowerIdentificationCard->verifiedTaxpayer;
                            $verified_restricted        = $borrowerIdentificationCard->verifiedRestricted;
                            return [
                                view("admin::loan_item_verification", [
                                    "verified_debtor"     => $verified_debtor,
                                    "verified_taxpayer"   => $verified_taxpayer,
                                    "verified_restricted" => $verified_restricted,
                                ]),
                            ];
                        } else {
                            return [view("admin::loan_item_verification", [])];
                        }
                    }),
                "<hr>",


                AdminFormElement::columns()
                    ->addColumn(function () {
                        return [
                            "<h3>Платежные реквизиты</h3>",
                            AdminFormElement::text('borrower.borrowerBankAccount.number', 'Номер банковского счета'),

                        ];
                    })
                    ->addColumn(function () {
                        return [
                            "<h3>Адрес регистрации</h3>",
                            AdminFormElement::select('borrower.borrowerAddress.ra_city_id', 'Город', City::class)
                                ->setDisplay("name"),
                            AdminFormElement::text('borrower.borrowerAddress.ra_street_name', 'Улица'),
                            AdminFormElement::text('borrower.borrowerAddress.ra_house_number', 'Номер дома'),
                            AdminFormElement::text('borrower.borrowerAddress.ra_apartment_number', 'Квартира'),
                            AdminFormElement::text('borrower.borrowerAddress.ra_postcode', 'Почтовый индекс'),
                            AdminFormElement::text('borrower.borrowerAddress.full_address', 'Ввод адреса вручную'),
                        ];
                    })->addColumn(function () {
                        return [
                            "<h3>Адрес прописки</h3>",
                            AdminFormElement::textarea('borrower.place_of_residence', '&nbsp;')->setRows(5),
                        ];
                    }),
                "</hr>",

                AdminFormElement::columns()
                    ->addColumn(function () use ($borrower_loan) {
                        return [
//                           AdminFormElement::custom()->setDisplay(function (\Illuminate\Database\Eloquent\Model $borrowerLoan) {
//                               if ($borrowerLoan->borrower) {
//
//                                   if(empty($borrowerLoan->borrowerLoanAgreementDocument)) {
//                                       $borrowerLoanAgreementDocument = BorrowerLoanAgreementDocument::create(["document_check_status_id" => 1]);
//                                       $borrowerLoan->borrowerLoanAgreementDocument()->save($borrowerLoanAgreementDocument);
//                                   }
//                                   $pledge_agreement_file_path = $borrowerLoan->borrowerLoanAgreementDocument->pledge_agreement_file_path; //договор залога
//                                   if (!empty($pledge_agreement_file_path)) {
//                                       return "<h3>Договор залога<a style='    font-size: 18pt; float: right;' href='" . asset($pledge_agreement_file_path) . "' target='_blank'>Открыть PDF оригинал</a></h3>";
//                                   } else {
//                                       return "<h3>Договор залога</h3>";
//                                   }
//                               }
//                           }),

                            AdminFormElement::file('borrowerLoanAgreementDocument.pledge_agreement_file_path', 'PDF оригинал'),
                            AdminFormElement::images('borrowerLoanAgreementDocument.file_paths', 'Договор залога')
                                ->setSaveCallback(function ($file, $path, $filename, $settings) use ($borrower_loan) {
                                    $new_path = FileHelper::uploadFile($file, "/account/{$borrower_loan->borrower_id}/documents");
                                    return ['value' => $new_path];
                                }),
                            AdminFormElement::textarea('borrowerLoanAgreementDocument.comment', 'Комментарий менеджера'),
                            AdminFormElement::select('borrowerLoanAgreementDocument.document_check_status_id', 'Статус проверки', DocumentCheckStatus::class)
                                ->setDisplay('text')
                                ->setSortable(false)
                        ];
                    })->addColumn(function () use ($borrower_loan) {
                        return [
                            "<h3>Скан удостоверения личности</h3>",
                            AdminFormElement::images('borrower.borrowerIdCardDocument.images', 'Фото')
                                ->setSaveCallback(function ($file, $path, $filename, $settings) use ($borrower_loan) {
                                    $new_path = FileHelper::uploadFile($file, "/account/{$borrower_loan->borrower_id}/documents");
                                    return ['value' => $new_path];
                                }),
                            AdminFormElement::view('admin::ready_answer_options'),
                            AdminFormElement::textarea('borrower.borrowerIdCardDocument.comment', 'Или введите свой комментарий'),
                            AdminFormElement::select('borrower.borrowerIdCardDocument.document_check_status_id', 'Статус проверки', DocumentCheckStatus::class)
                                ->setDisplay('text')
                                ->setSortable(false)
                        ];
                    })->addColumn(function () use ($borrower_loan) {
                        return [
                            "<h3>Адресная справка</h3>",
                            AdminFormElement::images('borrower.borrowerAddressDocument.images', 'Фото')
                                ->setSaveCallback(function ($file, $path, $filename, $settings) use ($borrower_loan) {
                                    $new_path = FileHelper::uploadFile($file, "/account/{$borrower_loan->borrower_id}/documents");
                                    return ['value' => $new_path];
                                }),
                            AdminFormElement::view('admin::ready_answer_options'),
                            AdminFormElement::textarea('borrower.borrowerAddressDocument.comment', 'Или введите свой комментарий'),
                            AdminFormElement::select('borrower.borrowerAddressDocument.document_check_status_id', 'Статус проверки', DocumentCheckStatus::class)
                                ->setDisplay('text')
                                ->setSortable(false)
                        ];
                    })->addColumn(function () use ($borrower_loan) {
                        return [
                            "<h3>Справка о пенс. отчислениях</h3>",
                            AdminFormElement::images('borrower.borrowerPensionDocument.images', 'Фото')
                                ->setSaveCallback(function ($file, $path, $filename, $settings) use ($borrower_loan) {
                                    $new_path = FileHelper::uploadFile($file, "/account/{$borrower_loan->borrower_id}/documents");
                                    return ['value' => $new_path];
                                }),
                            AdminFormElement::view('admin::ready_answer_options'),
                            AdminFormElement::textarea('borrower.borrowerPensionDocument.comment', 'Или введите свой комментарий'),
                            AdminFormElement::select('borrower.borrowerPensionDocument.document_check_status_id', 'Статус проверки', DocumentCheckStatus::class)
                                ->setDisplay('text')
                                ->setSortable(false)
                        ];
                    }),
            ]), 'Основная информация');
        }

        $tabs->appendTab(new \SleepingOwl\Admin\Form\FormElements([
            AdminFormElement::columns()
                ->addColumn(function () {
                    return [
                        AdminFormElement::number('duration_actual', 'Действующий срок займа')
                            ->setHtmlAttribute("readonly", "true")
                            ->setDefaultValue(0),
                        AdminFormElement::number('paid_sum', 'Сумма погашенная клиентом')
                            ->setDefaultValue(0),
                        AdminFormElement::number('customer_paid_sum', 'Внести сумму продления займа')
                            ->setDefaultValue(0),
                    ];
                })->addColumn(function () {
                    return [
                        AdminFormElement::number('reward_sum', 'Сумма вознаграждения')
                            ->setHtmlAttribute("readonly", "true")
                            ->setDefaultValue(0),
                        AdminFormElement::number('fine_interest_rate', 'Процентная ставка при просрочке')
                            ->setHtmlAttribute("readonly", "true")
                            ->setDefaultValue(0),
//                        AdminFormElement::number('fine_sum', 'Сумма начисленного пени')
//                            ->setHtmlAttribute("readonly", "true")
//                            ->setDefaultValue(0),
                        AdminFormElement::number('dealy_days', 'Кол-во дней просрочки')
                            ->setHtmlAttribute("readonly", "true")
                            ->setDefaultValue(0),
                    ];
                })->addColumn(function () {
                    return [
                        AdminFormElement::number('penalty_sum', 'Сумма начисленного единовременного штрафа (МРП)')
                            ->setDefaultValue(0),
                        AdminFormElement::number('notary_sum', 'Сумма за нотариальную подпись')
                            ->setDefaultValue(0),
                        AdminFormElement::number('judgment_sum', 'Сумма за судебные услуги')
                            ->setDefaultValue(0),
                    ];
                }),
        ]), 'Дополнительная инфомрация');


        if ($borrower_loans) {
            $tabs->appendTab(new \SleepingOwl\Admin\Form\FormElements([
                AdminFormElement::columns()
                    ->addColumn(function () use ($borrower_loans) {
                        return [
                            $borrower_loans
                        ];
                    }),
            ]), 'История займов');
        }

        $form->addElement($tabs);

        return $form;
    }


    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {
        // remove if unused
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }


}
