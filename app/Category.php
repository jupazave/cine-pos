<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	public function event() {
		return $this->hasOne('App\Event', 'event_id');
	}

    protected $guarded = [];
    protected $hidden = [
        'created_at', 'updated_at',
    ];


    public static $createRules = [
        'name' => 'required|min:3|max:35',
        'description' => 'required|min:5',
    ];

    public static $updateRules = [
        'name' => 'min:3|max:35',
        'description' => 'min:5',
    ];
}
