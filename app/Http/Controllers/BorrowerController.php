<?php
namespace App\Http\Controllers;

use App\Events\LoanCreated;
use App\Helpers\AppHelper;
use App\Helpers\FileHelper;
use App\Helpers\StatusHelper;
use App\Http\Requests\ConfirmationCodePost;
use App\Http\Requests\LoadBorrowerDocumentsPost;
use App\Http\Requests\ResetPasswordPost;
use App\Http\Requests\StoreBorrowerPost;
use App\Http\Requests\UpdateBorrowerBankAccountPost;
use App\Http\Requests\UpdateBorrowerPost;
use App\Model\Borrower;
use App\Model\BorrowerAddress;
use App\Model\BorrowerAddressDocument;
use App\Model\BorrowerBankAccounts;
use App\Model\BorrowerEmployment;
use App\Model\BorrowerIdCardDocument;
use App\Model\BorrowerIdentificationCard;
use App\Model\BorrowerLoan;
use App\Model\BorrowerLoanAgreementDocument;
use App\Model\BorrowerPensionDocument;
use App\Services\BorrowerService;
use App\Services\LoanService;
use App\Traits\SMSSender;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    use SMSSender;

    /**
     * Store a newly created resource in storage.
     *
     * @param BorrowerService   $borrowerService
     * @param StoreBorrowerPost $request
     * @return \Illuminate\Http\Response
     */
    public function store(BorrowerService $borrowerService, StoreBorrowerPost $request)
    {
        $response_data = [];
        //Записываем в сессию данные с формы
        $borrower_registration_data                 = $request->all();
        $borrower_registration_data["phone_number"] = AppHelper::toNumeric($borrower_registration_data["phone_number"]);
        $request->session()->put('borrower_registration_data', $borrower_registration_data);

        //Отправляем СМС с кодом
        if ($this->sendConfirmation($request->session()->get('borrower_registration_data.phone_number'))) {
            $response_data["status"]  = 1;
            $response_data["message"] = "Код отправлен";
        } else {
            $response_data["status"]  = 0;
            $response_data["message"] = "Код уже был отправлен ранее";
        }

        return response()->json($response_data, 200, ['Content-type' => 'application/json; charset=utf-8'],
            JSON_UNESCAPED_UNICODE);
    }


    /**
     * Подверждение регистрации
     *
     * @param ConfirmationCodePost $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkConfirmationCode(ConfirmationCodePost $request, LoanService $loanService)
    {
        $response_data = [];
        $is_confirmed = $request->session()->get("borrower_registration_data.is_confirmed");

        //Если код ещё не был подвержден, регистриуем заёмщика
        if ($is_confirmed != true) {
            if ($request->input('code') == $request->session()->get('sms_code')) {
                if ($this->registerBorrower($loanService)) {
                    $request->session()->put("borrower_registration_data.is_confirmed", true);
                    //Если код подвтержден и пользователь успешно добавлен в бд, переадрессовываем пользователя в личный кабинет
                    $response_data["redirect"] = action('AccountController@index');
                }
            } else {
                $response_data["status"]  = 0;
                $response_data["message"] = "Код введен неверно";
            }
        } else {
            //$request->session()->forget('borrower_registration_data.is_confirmed'); //test
            $response_data["redirect"] = action('AccountController@index');
        }

        return response()->json($response_data);
    }


    /**
     * Регистарция данных заёмщика (из сессии в БД)
     *
     * @param Request $request
     */
    protected function registerBorrower(LoanService $loanService)
    {
        //Генерируем пароль
        $new_password                     = AppHelper::randomPassword();
        $borrower_registration_collection = collect(session('borrower_registration_data'));
        $borrower_registration_data       = $borrower_registration_collection->only([
            'phone_number',
            'lastname',
            'firstname',
            'fathername',
            'email',
            'place_of_residence',
        ])
            ->put("password", bcrypt($new_password))
            ->all();
        $borrower_loan_registration_data  = $borrower_registration_collection->only([
            'sum',
            'duration_agreement'
        ])->all();
        //Формируем первоначальную информацию о займе по сумме и сроку
        $borrower_loan_registration_data       = $loanService->buildLoanRegistrationData($borrower_loan_registration_data["sum"],
            $borrower_loan_registration_data["duration_agreement"]);
        $borrower_employment_registration_data = $borrower_registration_collection->only([
            'work_place',
            'working_position',
            'salary',
            'salary_obtaining_method_id'
        ])->all();
        $borrower_identification_card_data     = $borrower_registration_collection->only(["IIN"])->all();
        $bank_account_number                   = $borrower_registration_collection->get("bank_account_number");
        $borrower_bank_account_data            = ["number" => $bank_account_number];

        //Добавляем информацию о заёмщике в БД
        $borrower    = Borrower::create($borrower_registration_data);
        $borrower_id = $borrower->id;

        //Добавляем информацию о трудоустройстве
        $borrowerEmployment = BorrowerEmployment::create($borrower_employment_registration_data);
        $borrower->borrowerEmployment()->save($borrowerEmployment);

        //Добавляем информацию об удостоверении личности
        $borrowerIdCard = BorrowerIdentificationCard::create($borrower_identification_card_data);
        $borrower->borrowerIdentificationCard()->save($borrowerIdCard);

        //Добавляем информацию о банковском счете
        $borrowerBankAccount = BorrowerBankAccounts::create($borrower_bank_account_data);
        $borrower->borrowerBankAccount()->save($borrowerBankAccount);

        //Адрес проживания и регистрации
        $borrowerAddress = BorrowerAddress::create([]);
        $borrower->borrowerAddress()->save($borrowerAddress);

        //Предварительно добавляем записи в таблицу для загружаемых документов
        $borrowerAddressDocument = BorrowerAddressDocument::create(["document_check_status_id" => 1]);
        $borrower->borrowerAddressDocument()->save($borrowerAddressDocument);
        $borrowerPensionDocument = BorrowerPensionDocument::create(["document_check_status_id" => 1]);
        $borrower->borrowerPensionDocument()->save($borrowerPensionDocument);
        $borrowerIdCardDocument = BorrowerIdCardDocument::create(["document_check_status_id" => 1]);
        $borrower->borrowerIdCardDocument()->save($borrowerIdCardDocument);

        //Добавляем информацию о займе в БД
        $borrowerLoan                  = BorrowerLoan::create($borrower_loan_registration_data);
        $borrower_loan                 = $borrower->borrowerLoans()->save($borrowerLoan);
        $borrowerLoanAgreementDocument = BorrowerLoanAgreementDocument::create(["document_check_status_id" => 1]);
        $borrower_loan->borrowerLoanAgreementDocument()->save($borrowerLoanAgreementDocument);

        //Собыие добавления новго займа
        event(new LoanCreated($borrower_loan));

        if ($borrower_id > 0) {
            //Отправляем новый пароль SMS сообщением
            $this->sendMessage($borrower_registration_data["phone_number"],
                "Доступы к avansplus.kz\nВаш логин: {$borrower_registration_data["phone_number"]}\nВаш пароль: {$new_password}");
            session(["borrower_id" => $borrower_id]);
            session()->forget("borrower_registration_data");

            return true;
        } else {
            return true;
        }
    }

    /**
     * Восстановление пароля
     *
     * @param Request $request
     */
    public function resetPassword(ResetPasswordPost $request, BorrowerService $borrowerService)
    {
        $response_data = [];
        $phone_number  = AppHelper::toNumeric($request->input("phone_number"));
        $borrower      = $borrowerService->borrowerByPhoneNumber($phone_number);

        if ($borrower) {
            if ($this->sendRecoveryCode($phone_number)) {
                $response_data["status"]  = true;
                $response_data["message"] = "Код восстановления отправлен на указанный телефон";
            } else {
                $response_data["status"]  = true;
                $response_data["message"] = "Код уже был отправлен";
            }
        } else {
            $response_data["status"]  = false;
            $response_data["message"] = "Пользователь не найден";
        }

        return response()->json($response_data);
    }

    /**
     * Новый пароль
     *
     * @param Request $request
     */
    public function addNewPassword(Request $request, BorrowerService $borrowerService)
    {
        $response_data  = [];
        $recovery_code  = $request->session()->get("recovery_data.code");
        $recovery_phone = $request->session()->get("recovery_data.phone");
        $new_password   = $request->input("new_password");

        if ($recovery_code == $request->input("recovery_code")) {

            $borrower           = $borrowerService->borrowerByPhoneNumber($recovery_phone);
            $borrower->password = bcrypt($new_password);
            $borrower->save();

            $response_data["status"]  = true;
            $response_data["message"] = "Пароль успешно изменен";

        } else {
            $response_data["status"]  = false;
            $response_data["message"] = "Неверный код восстановления";
        }

        return response()->json($response_data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param BorrowerService    $borrowerService
     * @param UpdateBorrowerPost $request
     * @param id $
     * @return \Illuminate\Http\Response
     */
    public function update(BorrowerService $borrowerService, UpdateBorrowerPost $request, $id)
    {

        $borrower = $borrowerService->borrower();

        $borrower_inputs = $request->only([
            'lastname',
            'firstname',
            'fathername',
            'gender_id',
            'DOB',
            'place_birth',
            'marital_status_id',
        ]);

        //Переводим дату в формат СНГ
        $borrower_inputs["DOB"] = date('Y-m-d', strtotime($borrower_inputs["DOB"]));

        $borrower->fill($borrower_inputs);
        $borrower->save();

        $borrower_identification_card_inputs = $request->only([
            'borrower_identification_card.number',
            'borrower_identification_card.issue_date',
            'borrower_identification_card.expiration_date',
            'borrower_identification_card.issued_authority_id',
        ]);

        //Переводим дату в формат СНГ
        $borrower_identification_card_inputs["borrower_identification_card"]["issue_date"]      = date('Y-m-d',
            strtotime($borrower_identification_card_inputs["borrower_identification_card"]["issue_date"]));
        $borrower_identification_card_inputs["borrower_identification_card"]["expiration_date"] = date('Y-m-d',
            strtotime($borrower_identification_card_inputs["borrower_identification_card"]["expiration_date"]));

        $borrower->borrowerIdentificationCard->fill($borrower_identification_card_inputs["borrower_identification_card"]);
        $borrower->borrowerIdentificationCard->save();


        $response_data["status"]  = true;
        $response_data["message"] = "Данные успешно изменены";

        return response()->json($response_data);
    }


    /**
     * Сохранение информации о банковском счете)
     *
     * @param BorrowerService               $borrowerService
     * @param UpdateBorrowerBankAccountPost $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBankAccountNumber(BorrowerService $borrowerService, UpdateBorrowerBankAccountPost $request)
    {
        $borrower = $borrowerService->borrower();
        $borrower->borrowerBankAccount->fill($request->only('number'));
        $borrower->borrowerBankAccount->save();

        return response()->json([
            'status'  => true,
            'message' => 'Платежные реквизиты успешно сохранены',
        ]);
    }

    /**
     * Загрузка документов
     *
     * @param BorrowerService           $borrowerService
     * @param LoanService               $loanService
     * @param LoadBorrowerDocumentsPost $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadDocuments(BorrowerService $borrowerService, LoadBorrowerDocumentsPost $request)
    {
        $borrower               = $borrowerService->borrower();
        $id_card_document_1     = $request->file('id_card_document_1');
        $id_card_document_2     = $request->file('id_card_document_2');
        $address_document_files = $request->file('address_documents');
        $pension_document_files = $request->file('pension_documents');
        $id_card_document_files = [$id_card_document_1, $id_card_document_2];

        if (!empty($id_card_document_1) || !empty($id_card_document_2)) {
            //4 - статус 'проверено'
            if ($borrower->borrowerIdCardDocument
                && $borrower->borrowerIdCardDocument->document_check_status_id != 4
                || !$borrower->borrowerIdCardDocument
            ) {
                $files = [];

                foreach ($id_card_document_files as $id_card_document_file) {
                    if(!empty($id_card_document_file)){
                        $files[] = FileHelper::uploadFile($id_card_document_file, "/account/{$borrower->id}/documents");
                    }
                }

                //Если документы удостоверения ранее были сохранены и переданы повторно, удаляем прежние исохраняем новые
                if (!empty($files)) {
                    if ($borrower->borrowerIdCardDocument && !empty($borrower->borrowerIdCardDocument->images)) {
                        //Удаляем ранее загруженный файл
                        foreach ($borrower->borrowerIdCardDocument->images as $image) {
                            FileHelper::deleteFile($image);
                        }
                    }

                    $this->fillOrSave(
                        $borrower->borrowerIdCardDocument(),
                        $borrower->borrowerIdCardDocument,
                        new BorrowerIdCardDocument, [
                            "images"                   => $files,
                            "document_check_status_id" => 2, //На проверке
                        ]
                    );
                }
            }
        }

        if ($address_document_files) {
            //4 - статус 'проверено'
            if ($borrower->borrowerAddressDocument && $borrower->borrowerAddressDocument->document_check_status_id != 4 || $borrower->borrowerAddressDocument == null) {

                if ($borrower->borrowerAddressDocument && !empty($borrower->borrowerAddressDocument->images)) {
                    //Удаляем ранее загруженный файл
                    foreach ($borrower->borrowerAddressDocument->images as $image) {
                        FileHelper::deleteFile($image);
                    }
                }

                $files = [];

                foreach ($address_document_files as $address_document_file) {
                    $files[] = FileHelper::uploadFile($address_document_file, "/account/{$borrower->id}/documents");
                }

                $this->fillOrSave(
                    $borrower->borrowerAddressDocument(),
                    $borrower->borrowerAddressDocument,
                    new BorrowerAddressDocument, [
                        "images"                   => $files,
                        "document_check_status_id" => 2, //На проверке
                    ]
                );
            }
        }

        if ($pension_document_files) {
            //4 - статус 'проверено'
            if ($borrower->borrowerPensionDocument && $borrower->borrowerPensionDocument->document_check_status_id != 4 || $borrower->borrowerPensionDocument == null) {
                if ($borrower->borrowerPensionDocument && !empty($borrower->borrowerPensionDocument->images)) {
                    //Удаляем ранее загруженный файл
                    foreach ($borrower->borrowerPensionDocument->images as $image) {
                        FileHelper::deleteFile($image);
                    }
                }

                $files = [];

                foreach ($pension_document_files as $pension_document_file) {
                    if ($pension_document_file != null) {
                        $files[] = FileHelper::uploadFile($pension_document_file, "/account/{$borrower->id}/documents");
                    }
                }

                $this->fillOrSave(
                    $borrower->borrowerPensionDocument(),
                    $borrower->borrowerPensionDocument,
                    new BorrowerPensionDocument, [
                        "images"                   => $files,
                        "document_check_status_id" => 2, //На проверке
                    ]
                );
            }
        }

        $borrower->borrowerLoans()->activeLoan()->first()->changeStatus(StatusHelper::DOCUMENTS_UPLOADED_BY_CLIENT); //3 - документы згружены

        $response_data["status"]   = true;
        $response_data["redirect"] = action("AccountController@index");
        $response_data["message"]  = "Докумены успешно загружены";

        return response()->json($response_data);
    }

}
