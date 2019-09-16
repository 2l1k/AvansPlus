<?php
namespace App\Http\Controllers;

use App\Events\LoanCreated;
use App\Helpers\ContractHelper;
use App\Helpers\FileHelper;
use App\Helpers\MailHelper;
use App\Http\Requests\AddNewLoanPost;
use App\Http\Requests\ConfirmationCodePost;
use App\Http\Requests\LoadBorrowerAgreementDocumentsPost;
use App\Model\BorrowerLoan;
use App\Model\BorrowerLoanAgreementDocument;
use App\Presenters\LoanPresenter;
use App\Services\BorrowerService;
use App\Services\LoanService;
use App\Helpers\StatusHelper;
use App\Traits\SMSSender;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    use SMSSender;

    /**
     * Добавление новго займа
     *
     * @param BorrowerService $borrowerService
     * @param LoanService     $loanService
     * @param AddNewLoanPost  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNewLoan(BorrowerService $borrowerService, LoanService $loanService, AddNewLoanPost $request)
    {
        $response_data = [];
        $borrower      = $borrowerService->borrower();
        $activeLoan    = $borrower->borrowerLoans()->activeLoan()->first();

        $new_loan_data = $request->all();
        if (!empty($activeLoan)) {
            $response_data["status"]  = 0;
            $response_data["message"] = "У Вас уже имеется действующий займ";
        } else {
            $borrower_loan_registration_data = $loanService->buildLoanRegistrationData($new_loan_data["sum"],
                $new_loan_data["duration_agreement"]);
            //Добавляем информацию о займе в БД
            $borrowerLoan                  = BorrowerLoan::create($borrower_loan_registration_data);
            $borrower_loan                 = $borrower->borrowerLoans()->save($borrowerLoan);
            $borrowerLoanAgreementDocument = BorrowerLoanAgreementDocument::create(["document_check_status_id" => 1]);
            $borrower_loan->borrowerLoanAgreementDocument()->save($borrowerLoanAgreementDocument);

            //Собыие добавления новго займа
            event(new LoanCreated($borrower_loan));

            $response_data["status"]   = 1;
            $response_data["message"]  = "Займ сформирован";
            $response_data["redirect"] = action("AccountController@index");
        }

        return response()->json($response_data);
    }


    /**
     * @param BorrowerService                    $borrowerService
     * @param LoanService                        $loanService
     * @param LoadBorrowerAgreementDocumentsPost $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadAgreementDocuments(
        BorrowerService $borrowerService,
        LoanService $loanService,
        LoadBorrowerAgreementDocumentsPost $request
    ) {
        $borrower                 = $borrowerService->borrower();
        $activeLoan               = $borrower->borrowerLoans()->activeLoan()->first();
        $agreement_document_files = $request->file('agreement_documents');

        if ($agreement_document_files) {
            //Если документ не в статусе "проверен" или ещё не был загружен ранее
            if ($activeLoan->borrowerLoanAgreementDocument && $activeLoan->borrowerLoanAgreementDocument->document_check_status_id != 4 || $borrower->borrowerLoanAgreementDocument == null) {
                if ($activeLoan->borrowerLoanAgreementDocument && !empty($activeLoan->borrowerLoanAgreementDocument->file_paths)) {
                    foreach ($activeLoan->borrowerLoanAgreementDocument->file_paths as $file_path) {
                        FileHelper::deleteFile($file_path);
                    }
                }
                $files = [];

                foreach ($agreement_document_files as $agreement_document_file) {
                    $files[] = FileHelper::uploadFile($agreement_document_file, "/account/{$borrower->id}/documents");
                }

                $this->fillOrSave($activeLoan->borrowerLoanAgreementDocument(),
                    $activeLoan->borrowerLoanAgreementDocument, new BorrowerLoanAgreementDocument, [
                        "file_paths"               => $files,
                        "document_check_status_id" => 2, //На проверке
                    ]);
            }
        }
        $borrower->borrowerLoans()->activeLoan()->first()->changeStatus(StatusHelper::CONTRACT_UPLOADED_BY_CLIENT); //7 - договор загружен клиентом

        $response_data["status"]   = true;
        $response_data["redirect"] = action("AccountController@index");
        $response_data["message"]  = "Договор успешно загружен";

        return response()->json($response_data);
    }

    /**
     * @param Request         $request
     * @param BorrowerService $borrowerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendConfirmationCode(Request $request, BorrowerService $borrowerService)
    {
        $response_data = [];
        $borrower      = $borrowerService->borrower();

        if ($this->sendConfirmation($borrower->phone_number)) {
            $response_data["status"]  = 1;
            $response_data["message"] = "Код отправлен";
        } else {
            $response_data["status"]  = 0;
            $response_data["message"] = "Код уже был отправлен ранее";
        }

        return response()->json($response_data);
    }

    /**
     * @param ConfirmationCodePost $request
     * @param LoanService          $loanService
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkConfirmationCode(ConfirmationCodePost $request, BorrowerService $borrowerService)
    {
        $response_data = [];
        $borrower      = $borrowerService->borrower();
        $is_confirmed  = $request->session()->get("loan_registration.is_confirmed");

        //Если код ещё не был подвержден, регистриуем заёмщика
        if ($is_confirmed != true) {
            if ($request->input('code') == $request->session()->get('sms_code')) {
                //Если код подвтержден, меняем статус у займа на подтвержденный
                $borrower->borrowerLoans()->activeLoan()->first()->changeStatus(StatusHelper::APPLICATION_CONFIRMED); // заявка подверждена
                $request->session()->put("loan_registration.is_confirmed", true);
                $response_data["redirect"] = action('AccountController@index');
            } else {
                $response_data["status"]  = 0;
                $response_data["message"] = "Код введен неверно";
            }
        } else {
            $response_data["redirect"] = action('AccountController@index');
        }

        return response()->json($response_data);
    }


    /**
     * Отправялем договор залога на почту
     *
     * @param BorrowerService $borrowerService
     * @param Request         $request
     */
    public function sendPledgeAgreement(LoanService $loanService, BorrowerService $borrowerService, Request $request)
    {
        $response_data = [];
        $borrower      = $borrowerService->borrower();

        $loan = $borrower->borrowerLoans()->activeLoan()->first();

        //Обновляем значения по займу согласно встречному предложению
        $borrower->borrowerLoans()->activeLoan()->update($loanService->calculateLoanData($loan->counteroffer_sum,
            $loan->counteroffer_duration_agreement));

        $loan = $borrower->borrowerLoans()->activeLoan()->first();


        if ($this->generatePledgeAgreement($loan)) {

            $pledge_agreement_file_path = url($loan->borrowerLoanAgreementDocument->pledge_agreement_file_path);
            MailHelper::sendAgreement([
                "to"          => $borrower->email,
                "to_name"     => $borrower->full_name,
                "attachments" => [$pledge_agreement_file_path],
                "loan"        => $loan
            ]);

            //$response_data["pledge_agreement_html"] = $pledge_agreement_html;
            $response_data["pledge_agreement_html"]      = view('docs.pdf.pledge_agreement',
                ["loan" => new LoanPresenter($loan)])->render();
            $response_data["pledge_agreement_file_path"] = $pledge_agreement_file_path;
            $response_data["javascript"]                 = '$("#pledge_agreement_file_download_btn").attr("data-url", "' . asset($pledge_agreement_file_path) . '")';
            $response_data["status"]                     = 1;
            $response_data["message"]                    = "Письмо отправлено на почту";
        } else {
            $response_data["status"]  = 0;
            $response_data["message"] = "Произошла ошибка при генерации документа";
        }

        return response()->json($response_data);
    }

    /**
     * Формируем договор залога для подписания
     *
     * @param BorrowerService $borrowerService
     * @param Request         $request
     */
    public function generatePledgeAgreement($loan)
    {
        $pledge_agreement_file_path = ContractHelper::generatePledgeAgreement($loan);

        if ($pledge_agreement_file_path != false) {
            $loan->borrowerLoanAgreementDocument->fill([
                "pledge_agreement_file_path" => $pledge_agreement_file_path
            ]);
            $loan->borrowerLoanAgreementDocument->save();

            return $pledge_agreement_file_path;
        }

        return false;
    }


    /**
     * Проверка статуса действующего займа (для периодического обновления страницы)
     *
     * @param BorrowerService $borrowerService
     * @param Request         $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus(BorrowerService $borrowerService, Request $request)
    {
        $borrower       = $borrowerService->borrower();
        $active_loan    = $borrower->borrowerLoans()->activeLoan()->first();
        $loan_status_id = empty($active_loan->loan_status_id) ? 0 : $active_loan->loan_status_id;

        return response()->json([
            "loan_status_id" => $loan_status_id
        ]);
    }

    /**
     * Отказ от займа (на шаге оформления займа)
     *
     * @param BorrowerService $borrowerService
     * @param Request         $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refuse(BorrowerService $borrowerService, Request $request)
    {
        $borrower    = $borrowerService->borrower();
        $active_loan = $borrower->borrowerLoans()->activeLoan()->first();

        //Если статус займа - загрузка договора, то разрешаем переводить займ в статус отказанных
        if ($active_loan->loan_status_id == StatusHelper::LOADING_CONTRACT) {
            $response_data            = [];
            $response_data["status"]  = 1;
            $response_data["message"] = "Заявка на займ отменена";

            //Переводим в статус "Отказано"
            $active_loan->changeStatus(StatusHelper::REFUSED);
        }
        $response_data["redirect"] = action('AccountController@index');

        return response()->json($response_data);
    }
}



