<?php
namespace App\Http\Requests;

use App\Helpers\AppHelper;
use App\Model\Borrower;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoadBorrowerDocumentsPost extends FormRequest
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
        $rules              = [];
        $id_card_document_1 = $this->file('id_card_document_1');
        $id_card_document_2 = $this->file('id_card_document_2');
        $address_documents  = $this->file('address_documents');
        $pension_documents  = $this->file('pension_documents');

        $borrower = Borrower::findOrFail(session("borrower_id"));

        if (empty($id_card_document_1)
            && empty($id_card_document_2)
            && $borrower->borrowerIdCardDocument
            && empty($borrower->borrowerIdCardDocument->images)) {
            $rules['id_card_document_1'] = 'required';
            $rules['id_card_document_2'] = 'required';
        }

//        $rules['id_card_document_1'] = 'mimes:jpeg,bmp,png,pdf|max:10000';
//        $rules['id_card_document_2'] = 'mimes:jpeg,bmp,png,pdf|max:10000';

        if (!empty($pension_documents)) {
            foreach (range(0, $pension_documents) as $index) {
                //$rules['pension_documents' . $index] = 'mimes:jpeg,bmp,png,pdf|max:10000';
            }
        }
        if (!empty($address_documents)) {
            foreach (range(0, $address_documents) as $index) {
                //$rules['address_documents.' . $index] = 'mimes:jpeg,bmp,png,pdf|max:10000';
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
//            'id_card_document.required'  => 'Файл должен быть изображением в формате JPEG или PNG',
//            'id_card_document.image'  => 'Файл должен быть изображением в формате JPEG или PNG',
//            'address_document.image'  => 'Файл должен быть изображением в формате JPEG или PNG',
//            'pension_document.image'  => 'Файл должен быть изображением в формате JPEG или PNG',
        ];
    }


    public function withValidator(Validator $validator)
    {

        if ($validator->fails()) {
            echo collect([
                "status"  => 0,
                "message" => "Проверьте формат загружаемых файлов",
                "errors"  => $validator->messages()->toArray()
            ])->toJson();
            exit();
        }
    }
}
