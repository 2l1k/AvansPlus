<?php

namespace Admin\Http\Sections;

use AdminColumnFilter;
use AdminForm;
use AdminFormElement;
use AdminSection;
use App\Helpers\FileHelper;
use App\Model\Borrower;
use App\Model\Notification;
use App\Model\BorrowerIdentificationCard;
use App\Model\City;
use App\Model\DocumentCheckStatus;
use App\Model\IssuedAuthority;
use App\Model\LoansByBorrower;
use App\Model\SalaryObtainingMethod;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
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
class Borrowers extends Section
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
        return 'Заёмщики';
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display_table = AdminDisplay::datatablesAsync();

        $display_table->setApply(function ($query) {
            $query->orderBy('id', 'desc');
        });

        $display_table->setColumns([
            AdminColumn::text("lastname", "Фамилия"),
            AdminColumn::text("firstname", "Имя"),
            AdminColumn::text("fathername", "Отчество"),
            AdminColumn::text("borrowerIdentificationCard.IIN", "ИИН"),
            AdminColumn::text('email')->setLabel('Email')->setHtmlAttribute('class', 'text-muted'),
            AdminColumn::text('phone_number')->setLabel('Телефон'),
        ])->setColumnFilters([
            AdminColumnFilter::text()->setPlaceholder('Фамилия')->setOperator(FilterInterface::CONTAINS),
            AdminColumnFilter::text()->setPlaceholder('Имя')->setOperator(FilterInterface::CONTAINS),
            AdminColumnFilter::text()->setPlaceholder('Отчество')->setOperator(FilterInterface::CONTAINS),
            AdminColumnFilter::text()->setPlaceholder('ИИН')->setOperator(FilterInterface::CONTAINS),
            AdminColumnFilter::text()->setPlaceholder('Email')->setOperator(FilterInterface::CONTAINS),
            AdminColumnFilter::text()->setPlaceholder('Телефон')->setOperator(FilterInterface::CONTAINS),
        ]);

        $display_table->getColumnFilters()->setPlacement('table.header');

        $display_table->paginate(15);

        return $display_table;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($borrower_id)
    {
        $borrower = Borrower::find($borrower_id);
        $form = AdminForm::panel();

        $tabs = AdminDisplay::tabbed([
            'Контактная информация' => new \SleepingOwl\Admin\Form\FormElements([
                AdminFormElement::date('unlock_date', 'Бан-лист')
                    ->setDefaultValue(0),
                AdminFormElement::checkbox('is_banned', 'Добавить заёмщика в бан')
                    ->setHtmlAttribute("class", "pull-right"),
                "<h3>Анкета</h3>",
                AdminFormElement::columns()
                    ->addColumn(function () {
                        return [
                            AdminFormElement::text('lastname', 'Фамилия'),
                            AdminFormElement::text('firstname', 'Имя'),
                            AdminFormElement::text('fathername', 'Отчество'),
                            AdminFormElement::text('email', 'Email')->unique('Поле должно содержать уникальное значение')->required("Введите значение"),
                            AdminFormElement::text('phone_number', 'Телефон')->unique('Поле должно содержать уникальное значение')->required("Введите значение"),
                            AdminFormElement::date('DOB', 'Дата рождения')->setFormat('m-d-Y')->setHtmlAttribute("data-date", true),
                            AdminFormElement::password('password', 'Пароль')->mutateValue(function($value) {
                                if(strlen($value) > 20){
                                    return $value;
                                }else{
                                    return bcrypt($value);
                                }
                            }),

                        ];
                    })->addColumn(function () {
                        return [
                            AdminFormElement::text('borrowerIdentificationCard.IIN', 'ИИН')->required("Введите значение")->unique('Поле должно содержать уникальное значение'),
                            AdminFormElement::date('borrowerIdentificationCard.issue_date', 'Дата выдачи')->setHtmlAttribute("data-date", true),
                            AdminFormElement::date('borrowerIdentificationCard.expiration_date', 'Дата окончания')->setHtmlAttribute("data-date", true),
                            AdminFormElement::number('borrowerIdentificationCard.number', 'Номер удостоверения'),
                            AdminFormElement::select('borrowerIdentificationCard.issued_authority_id', 'Кем выдано', IssuedAuthority::class)
                                ->setDisplay("text"),
                        ];
                    })->addColumn(function () {
                        return [
                            AdminFormElement::text('borrowerEmployment.work_place', 'Место работы'),
                            AdminFormElement::text('borrowerEmployment.salary', 'Размер заработной платы'),
                            AdminFormElement::text('borrowerEmployment.working_position', 'Должность'),
                            AdminFormElement::select('borrowerEmployment.salary_obtaining_method_id', 'Способ получения зарплаты', SalaryObtainingMethod::class)
                                ->setDisplay("text")
                        ];
                    }),

                "<hr>",

                AdminFormElement::columns()
                    ->addColumn(function () use ($borrower) {
                        if ($borrower) {
                            $borrowerIdentificationCard = $borrower->borrowerIdentificationCard;
                            $verified_debtor = $borrowerIdentificationCard->verifiedDebtor;
                            $verified_taxpayer = $borrowerIdentificationCard->verifiedTaxpayer;
                            $verified_restricted = $borrowerIdentificationCard->verifiedRestricted;
                            return [
                                view("admin::loan_item_verification", [
                                    "verified_debtor" => $verified_debtor,
                                    "verified_taxpayer" => $verified_taxpayer,
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
                            AdminFormElement::text('borrowerBankAccount.number', 'Номер банковского счета'),
                        ];
                    })
                    ->addColumn(function () {
                        return [
                            "<h3>Адрес регистрации</h3>",
                            AdminFormElement::select('borrowerAddress.ra_city_id', 'Город', City::class)
                                ->setDisplay("name"),
                            AdminFormElement::text('borrowerAddress.ra_street_name', 'Улица'),
                            AdminFormElement::text('borrowerAddress.ra_house_number', 'Номер дома'),
                            AdminFormElement::text('borrowerAddress.ra_apartment_number', 'Квартира'),
                            AdminFormElement::text('borrowerAddress.ra_postcode', 'Почтовый индекс'),
                            AdminFormElement::text('borrowerAddress.full_address', 'Ввод адреса вручную'),

                        ];
                    })->addColumn(function () {
                      return [];
                    }),
                "</hr>",

                AdminFormElement::columns()
                    ->addColumn(function () use ($borrower) {
                        return [
                            "<h3>Скан удостоверения личности</h3>",
                            AdminFormElement::images('borrowerIdCardDocument.images', 'Фото')
                                ->setSaveCallback(function ($file, $path, $filename, $settings) use ($borrower) {
                                    $new_path = FileHelper::uploadFile($file, "/account/{$borrower->id}/documents");
                                    return ['value' => $new_path];
                                }),
                            AdminFormElement::textarea('borrowerIdCardDocument.comment', 'Комментарий менеджера'),
                            AdminFormElement::select('borrowerIdCardDocument.document_check_status_id', 'Статус проверки', DocumentCheckStatus::class)
                                ->setDisplay('text')
                                ->setSortable(false)
                        ];
                    })->addColumn(function () use ($borrower) {
                        return [
                            "<h3>Адресная справка</h3>",
                            AdminFormElement::images('borrowerAddressDocument.images', 'Фото')
                                ->setSaveCallback(function ($file, $path, $filename, $settings) use ($borrower) {
                                    $new_path = FileHelper::uploadFile($file, "/account/{$borrower->id}/documents");
                                    return ['value' => $new_path];
                                }),
                            AdminFormElement::textarea('borrowerAddressDocument.comment', 'Комментарий менеджера'),
                            AdminFormElement::select('borrowerAddressDocument.document_check_status_id', 'Статус проверки', DocumentCheckStatus::class)
                                ->setDisplay('text')
                                ->setSortable(false)
                        ];
                    })->addColumn(function () use ($borrower) {
                        return [
                            "<h3>Справка о пенс. отчислениях</h3>",
                            AdminFormElement::images('borrowerPensionDocument.images', 'Фото')
                                ->setSaveCallback(function ($file, $path, $filename, $settings) use ($borrower) {
                                    $new_path = FileHelper::uploadFile($file, "/account/{$borrower->id}/documents");
                                    return ['value' => $new_path];
                                }),
                            AdminFormElement::textarea('borrowerPensionDocument.comment', 'Комментарий менеджера'),
                            AdminFormElement::select('borrowerPensionDocument.document_check_status_id', 'Статус проверки', DocumentCheckStatus::class)
                                ->setDisplay('text')
                                ->setSortable(false)
                        ];
                    }),

            ]),
        ]);


        $borrower_loans = null;
        if (!empty($borrower_id)) {
            //Все займы по заёмщику
            $borrower_loans = AdminSection::getModel(LoansByBorrower::class)->fireDisplay();
            $borrower_loans->getScopes()->push(['loansByBorrowerId', $borrower_id]);
        }

        $borrower_sms_notifications = null;
        if (!empty($borrower_id)) {
            //Все СМС уведомления по заёмщику
            $borrower_sms_notifications = AdminSection::getModel(Notification::class)->fireDisplay();
            $borrower_sms_notifications->getScopes()->push(['smsByBorrowerId', $borrower_id]);
        }

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

        //Все СМС уведомления по заёмщику
        if ($borrower_sms_notifications) {
            $tabs->appendTab(new \SleepingOwl\Admin\Form\FormElements([
                AdminFormElement::columns()
                    ->addColumn(function () use ($borrower_sms_notifications) {
                        return [
                            $borrower_sms_notifications
                        ];
                    }),
            ]), 'История СМС');
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
    /*public function onDelete($id, \App\Model\Borrower $model)
    {
         $borrower = $model->find($id);
		$borrower->borrowerLoans()->delete();
    }*/

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
