<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{
    //
    protected $guarded = [];

    public static $createRules = [
        'name' => 'required|min:3|max:35',
        'description' => 'required|min:5',
    ];

    public static $updateRules = [
        'name' => 'min:3|max:35',
        'description' => 'min:5',
    ];
}
