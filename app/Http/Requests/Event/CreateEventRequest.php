<?php

namespace App\Http\Requests\Event;

use App\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateEventRequest extends FormRequest
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
        return Event::$createRules;
    }

    protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }

}