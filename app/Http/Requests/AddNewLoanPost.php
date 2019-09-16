<?php

namespace App\Http\Requests;

use App\Helpers\AppHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddNewLoanPost extends FormRequest
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
            'duration_agreement' => 'required|integer',
            'sum' => 'required|integer'
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
            'duration_agreement.required' => 'Укажите период',
            'duration_agreement.integer' => 'Укажите период',
            'sum.required' => 'Укажите сумму',
            'sum.integer' => 'Укажите сумму',
        ];
    }
}
