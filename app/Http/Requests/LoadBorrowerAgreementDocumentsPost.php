<?php

namespace App\Http\Requests;

use App\Helpers\AppHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoadBorrowerAgreementDocumentsPost extends FormRequest
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
        $rules = [];
        $agreement_documents = $this->file('agreement_documents');
        if(empty($agreement_documents)){
            $rules['agreement_documents'] = 'required';
        }else{
            foreach(range(0, $agreement_documents) as $index) {
                $rules['agreement_documents.' . $index] = 'mimes:jpeg,bmp,png,pdf|max:10000';
            }
        }
        return $rules;
    }

    /**
     * Получить сообщения об ошибках для определённых правил проверки.
     *
     * @return array
     */
    public function messages()
    {
        return [

            'agreement_documents.required'  => 'Загрузите файл'
        ];
    }


    public function withValidator(Validator $validator)
    {

        if ($validator->fails()) {
           echo collect([
               "status" => 0,
               "message" => "Проверьте формат загружаемых файлов",
               "errors" => $validator->messages()->toArray()])->toJson();
           exit();
        }
    }
}
