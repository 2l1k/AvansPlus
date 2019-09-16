<?php

namespace App\Http\Requests;

use App\Helpers\AppHelper;
use App\Model\Borrower;
use App\Repositories\BorrowerRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StoreBorrowerPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!empty(session("borrower_id"))){
            throw new HttpResponseException(response()->json(["redirect" => route("account.index")], 200));
        }
        return empty(session("borrower_id"));
        //return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'IIN' => 'required|digits:12',
            'phone_number' => 'required',
            'email' => 'required|email|unique:borrowers',
            'duration_agreement' => 'required|integer|between:30,30',
            'lastname' => 'required',
            'firstname' => 'required',
            'fathername' => 'required',
            'place_of_residence' => 'required',
            'work_place' => 'required',
            'working_position' => 'required',
            'salary' => 'required|integer',
            'salary_obtaining_method_id' => 'required|integer',
            'bank_account_number' => 'required|regex:/KZ([0-9a-zA-Z]){18}/',
            'sum' => 'required|integer|between:10000,50000'
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
            'IIN.required' => 'Необходимо указать ИИН',
            'IIN.digits' => 'Введите 12 цифр',
            'IIN.unique' => 'Данный ИИН уже зарегистрирован в системе',
            'phone_number.required'  => 'Необходимо указать номер телефона',
            'email.required'  => 'Введите свой почтовый ящик',
            'email.email'  => 'Данный email уже зарегистрирован в системе',
            'lastname.required'  => 'Заполните фамилию',
            'firstname.required'  => 'Заполните имя',
            'fathername.required'  => 'Заполните отчество',
            'place_of_residence.required' => 'Заполните адрес прописки',
            'work_place.required'  => 'Укажите место работы',
            'working_position.required'  => 'Введите свою должность',
            'salary.required'  => 'Введите сумму',
            'salary_obtaining_method_id.required'  => 'Выберите способ получения зарплаты',
            'salary_obtaining_method_id.integer'  => 'Выберите способ получения зарплаты',
            'bank_account_number.required'  => 'Введите номер банковского счета',
            'bank_account_number.regex'  => 'Введите номер банковского счета',
            'sum.required'  => 'Введите сумму',
            'sum.integer'  => 'Введите сумму',
            'sum.between'  => 'Введите сумму',
        ];
    }

    public function withValidator(Validator $validator)
    {

        $validator->after(function (Validator $validator) {
            $iin = $this->validationData()["IIN"];
            $phone_number =  AppHelper::toNumeric($this->validationData()["phone_number"]);

            if (!empty($iin) && AppHelper::checkValidIIN($iin) != true) {
                $validator->errors()->add('IIN', 'Введите существующий ИИН');
            }

            if (strlen($phone_number) != 11) {
                $validator->errors()->add('phone_number', 'Введите номер телефона');
            }


        });
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $response_data = ['errors' => $errors];
        $phone_number =  AppHelper::toNumeric($this->validationData()["phone_number"]);
        $IIN = $this->validationData()["IIN"];

        //Если ИИН введен корректно, проверяем его в БД на совпадение
        if(empty($errors["IIN"])){

            $borrowerRepository = new BorrowerRepository(new Borrower());
            $borrower = $borrowerRepository->findByIIN($IIN);
            if($borrower){
                $response_data["popup_error"] = "INN_exist";
            }

            //Если телефон введен корректно, проверяем его в БД
            if(empty($errors["phone_number"])){
                    $borrowerRepository = new BorrowerRepository(new Borrower());
                $borrower = $borrowerRepository->findByPhoneNumber($phone_number);
                if($borrower){
                    $response_data["popup_error"] = "phone_number_exist";
                }
            }
        }

        throw new HttpResponseException(response()->json($response_data, 200));
    }

}
