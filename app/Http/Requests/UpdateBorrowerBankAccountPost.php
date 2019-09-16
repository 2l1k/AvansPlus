<?php

namespace App\Http\Requests;

use App\Helpers\AppHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBorrowerBankAccountPost extends FormRequest
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
            'bank_account_number' => 'alpha_num|size:20',
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
            'bank_account_number.alpha_num'  => 'Введите номер банковского счета',
            'bank_account_number.size'  => 'Введите номер банковского счета',
        ];
    }


}
