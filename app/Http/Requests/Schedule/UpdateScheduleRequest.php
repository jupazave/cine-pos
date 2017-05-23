<?php

namespace App\Http\Requests\Schedule;

use App\Schedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Schedule::$updateRules;
    }

    protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
}
