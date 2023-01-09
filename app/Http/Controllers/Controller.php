<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function validate($data, $rules)
    {


        $validator = Validator::make($data->all(), $rules);
        if ($validator->fails()) {
            abort(error('Unable to process your request. Please make sure the data provided are valid', $validator->errors()->toArray()));
        }
        return $data;
    }

    public function saveData(array $data, $model, $id = null)
    {
        if ($id == null) {
            $data["created_at"] = Carbon::now();
            $data["updated_at"] = Carbon::now();

            $id =  $model::insertGetId($data);
        } else {
            $id = $model::whereId($id)->update($data);
        }
        return $id;
    }
}
