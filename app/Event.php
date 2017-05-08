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
}
