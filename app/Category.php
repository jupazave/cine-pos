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
}
