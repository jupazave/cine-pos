<?php
/**
 * Created by PhpStorm.
 * User: La_ma
 * Date: 18/05/2017
 * Time: 10:38 PM
 */

namespace App\Http\Requests;


use App\Review;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
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
        return Review::$updateRules;
    }
}