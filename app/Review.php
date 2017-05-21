<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $guarded = [];

    public static $createRules = [
        'name' => 'required|min:1',
        'score' => 'required|numeric|min:1|max:10',
        'event_id' => 'required|numeric'
    ];

    public static $updateRules = [
        'name' => 'min:1',
        'score' => 'numeric|min:1|max:10',
        'event_id' => 'numeric'
    ];
}
