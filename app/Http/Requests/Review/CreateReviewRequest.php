<?php
/**
 * Created by PhpStorm.
 * User: La_ma
 * Date: 18/05/2017
 * Time: 10:32 PM
 */

namespace App\Http\Requests\Review;


use App\Review;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateReviewRequest extends FormRequest {

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
        return Review::$createRules;
    }

    protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
}