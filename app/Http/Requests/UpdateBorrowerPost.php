<?php

namespace App\Http\Requests;

use App\Helpers\AppHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateBorrowerPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !empty(session("borrower_id"));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lastname' => 'required',
            'firstname' => 'required',
            'fathername' => 'required',
            'gender_id' => 'exists:genders,id',
            'DOB' => 'date',
            'place_birth' => 'required',
            'borrower_identification_card.number' => 'regex:/^[0][0-9]{8,}/',
            'borrower_identification_card.issue_date' => 'date',
            'borrower_identification_card.expiration_date' => 'date',
            'borrower_identification_card.issued_authority_id' => 'exists:issued_authorities,id',
            'marital_status_id' => 'exists:marital_statuses,id',
            'borrower_bank_account' => 'alpha_num|size:20',
        ];
    }

    /**
     * Получить сообщения об ошибках для определённых правил проверки.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'lastname.required'  => 'Введите фамилию',
            'firstname.required'  => 'Введите имя',
            'fathername.required'  => 'Введите отчество',
            'gender_id.exists'  => 'Выберите пол',
            'DOB.date'  => 'Введите дату',
            'place_birth.required'  => 'Введите место рождения',
            'borrower_identification_card.number.integer'  => 'Введите номер',
            'borrower_identification_card.issue_date.date'  => 'Введите дату',
            'borrower_identification_card.expiration_date.date'  => 'Введите дату',
            'borrower_identification_card.issued_authority_id.exists'  => 'Выберите значение из списка',
            'marital_status_id.exists'  => 'Выберите значение из списка',
            'place_birth.required'  => 'Введите место рождения',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
//        $errors = (new ValidationException($validator))->errors();
//        dd($errors);
//        $errors = (new ValidationException($validator))->errors();
//        $response_data = ['errors' => $errors];
//        $phone_number =  AppHelper::toNumeric($this->validationData()["phone_number"]);
//        $IIN = $this->validationData()["IIN"];
//
//        //Если ИИН введен корректно, проверяем его в БД на совпадение
//        if(empty($errors["IIN"])){
//
//            $borrowerRepository = new BorrowerRepository(new Borrower());
//            $borrower = $borrowerRepository->findByIIN($IIN);
//            if($borrower){
//                $response_data["popup_error"] = "INN_exist";
//            }
//
//            //Если телефон введен корректно, проверяем его в БД
//            if(empty($errors["phone_number"])){
//                $borrowerRepository = new BorrowerRepository(new Borrower());
//                $borrower = $borrowerRepository->findByPhoneNumber($phone_number);
//                if($borrower){
//                    $response_data["popup_error"] = "phone_number_exist";
//                }
//            }
//        }
//
//        throw new HttpResponseException(response()->json($response_data, 200));
    }


}
