<?php

namespace App\Http\Requests;

use App\Helpers\AppHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return true;
        return empty(session("borrower_id"));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone_number' => 'required',
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
        ];
    }

    public function withValidator(Validator $validator)
    {

        $validator->after(function (Validator $validator) {
            $phone_number =  AppHelper::toNumeric($this->validationData()["phone_number"]);

            if(strlen($phone_number) != 11){
                $validator->errors()->add('phone_number', 'Введите номер телефона');
            }

        });
    }
}
