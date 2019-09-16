<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\LoginPost;
use App\Model\BorrowerLoanNotification;
use \App\Presenters\LoanPresenter;
use App\Model\Gender;
use App\Model\IssuedAuthority;
use App\Model\MaritalStatus;
use App\Model\Notification;
use App\Services\BorrowerService;
use App\Services\LoanService;
use Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(BorrowerService $borrowerService, LoanService $loanService)
    {
        $borrower = $borrowerService->borrower();

        //Увдедомления для пользователя
        $borrowerLoanNotifications = $borrower->borrowerLoanNotifications()->notViewed()->get();




        $activeLoan = $borrower->borrowerLoans()->activeLoan()->first();
        $loans = $borrower->borrowerLoans();

        $issued_authorities = IssuedAuthority::all();
        $genders = Gender::all();
        $marital_statuses = MaritalStatus::all();

        if (!empty($activeLoan)) {

            $errors = [];
            $tooltips = [];
            //Если требуется повторная загрузка
            $borrowerAddressDocument = $borrower->borrowerAddressDocument;
            if ($borrowerAddressDocument && $borrowerAddressDocument->document_check_status_id == 3) {
                if (!empty($borrowerAddressDocument->comment)) {
                    $tooltips["address_documents[]"] = $borrowerAddressDocument->comment;
                }
            }
            $borrowerIdCardDocument = $borrower->borrowerIdCardDocument;
            if ($borrowerIdCardDocument && $borrowerIdCardDocument->document_check_status_id == 3) {
                if (!empty($borrowerIdCardDocument->comment)) {
                    $tooltips["id_card_document_1"] = $borrowerIdCardDocument->comment;
                }
            }
            $borrowerPensionDocument = $borrower->borrowerPensionDocument;
            if ($borrowerPensionDocument && $borrowerPensionDocument->document_check_status_id == 3) {
                if (!empty($borrowerPensionDocument->comment)) {
                    $tooltips["pension_documents[]"] = $borrowerPensionDocument->comment;
                }
            }
            $borrowerLoanAgreementDocument = $activeLoan->borrowerLoanAgreementDocument;
            if ($borrowerLoanAgreementDocument && $borrowerLoanAgreementDocument->document_check_status_id == 3) {
                if (!empty($borrowerLoanAgreementDocument->comment)) {
                    $errors["agreement_documents[]"] = $borrowerLoanAgreementDocument->comment;
                }
            }

            $active_loan = $loans->activeLoan()->first();
            $active_loan_status_id = ($active_loan) ? $active_loan->loan_status_id : false;
            $active_loan_status_category_id = ($active_loan) ? $active_loan->loan_status_category_id : false;

            $pledge_agreement_file_path = $active_loan->borrowerLoanAgreementDocument->pledge_agreement_file_path; //договор залога

            $loanPresenter = new LoanPresenter($active_loan);
            $response_data = [
                'borrower' => $borrower,
                'loans' => $borrower->borrowerLoans()->get(),
                'active_loan' => $loanPresenter,
                'active_loan_status_id' => $active_loan_status_id, //статус (состояние) займа
                'active_loan_status_category_id' => $active_loan_status_category_id, // категория статусов займа
                'pledge_agreement_file_path' => $pledge_agreement_file_path,
                'issued_authorities' => $issued_authorities,
                'genders' => $genders,
                'marital_statuses' => $marital_statuses,
                'errors' => $errors,
                'tooltips' => $tooltips
            ];
        } else {

            $response_data = [
                'borrower' => $borrower,
                'loans' => $borrower->borrowerLoans()->get(),
                'issued_authorities' => $issued_authorities,
                'genders' => $genders,
                'marital_statuses' => $marital_statuses
            ];
        }

        $response_data["borrower_loan_notifications"] = $borrowerLoanNotifications->toArray();


        return view('account.index', $response_data);
    }

    /**
     * Авторизация
     *
     * @param LoginPost $request
     * @param BorrowerService $borrowerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginPost $request, BorrowerService $borrowerService)
    {
        $response_data = [];
        $password = $request->input("password");
        $phone_number = AppHelper::toNumeric($request->input("phone_number"));
        $borrower = $borrowerService->borrowerByPhoneNumber($phone_number);

        if ($borrower && Hash::check($password, $borrower->password)) {
            session(["borrower_id" => $borrower->id]);
            $response_data["status"] = true;
            $response_data["redirect"] = action('AccountController@index');
        } else {
            $response_data["status"] = false;
            $response_data["message"] = "Неверный логин или пароль";
        }
        return response()->json($response_data);
    }

    // SMS Auth
    public function loginSms($id, $ref)
    {
        session(["borrower_id" => $id]);

        $enter = Notification::where('borrower_id',$id)->where('ref',$ref)->first();
        if ($enter->enter == null) {
            $enter = (int)1;
        } else {
            $enter = $enter->enter + (int)1;
        }
        Notification::where('borrower_id',$id)->where('ref',$ref)->update(array('enter'=>$enter));

        return redirect('account');
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect(route('home.index'));
    }


}
