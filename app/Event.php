<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    public function category() {
		return $this->belongsTo('App\Category');
	}
    protected $guarded = [];

    public static $createRules = [
        'name' => 'bail|required|unique:events|min:3|max:50',
        'description' => 'required|min:5',
        'director' => 'required',
        'cast' => 'required',
        'email' => 'email',
        'facebook' => 'url',
        'instagram' => 'url',
        'twitter' => 'url',
        'webpage' => 'url',
        'category_id' => 'required'
    ];

    public static $updateRules = [
        'name' => 'min:3|max:35',
        'description' => 'nullable|min:5',
        'email' => 'nullable|email',
        'facebook' => 'nullable|url',
        'instagram' => 'nullable|url',
        'twitter' => 'nullable|url',
        'webpage' => 'nullable|url',
    ];
}
