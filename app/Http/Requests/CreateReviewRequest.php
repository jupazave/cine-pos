<?php
/**
 * Created by PhpStorm.
 * User: La_ma
 * Date: 18/05/2017
 * Time: 10:32 PM
 */

namespace App\Http\Requests;


use App\Review;
use Illuminate\Foundation\Http\FormRequest;

class CreateReviewRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Review::$createRules;
    }
}