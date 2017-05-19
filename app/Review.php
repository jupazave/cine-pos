<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $guarded = [];

    public static $createRules = [
        'name' => 'required|min:5',
        'score' => 'required|numeric',
        'event_id' => 'required|numeric'
    ];

    public static $updateRules = [
        'name' => 'min:5',
        'score' => 'numeric',
        'event_id' => 'numeric'
    ];
}
