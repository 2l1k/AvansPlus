<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }

    protected function fillOrSave($relation, $model, $model_class, $data)
    {
        if($model){
            $model->fill($data);
            $model->save();
        }else{
            $model_class->fill($data);
            $relation->save($model_class);
        }
    }


}
