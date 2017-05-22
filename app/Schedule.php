<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;


class Schedule extends Model
{
    protected $guarded = [];

    protected $table = 'schedules';

    public function event() {
    	return $this->belongsTo('App\Event', 'event_id');
    }

    public function theaters() {
    	return $this->belongsTo('App\Theater');
    }
}
