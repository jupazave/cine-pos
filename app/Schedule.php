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

    public function theater() {
    	return $this->belongsTo('App\Theater', 'theater_id');
    }

    public static $createRules = [
        'stage' => 'min:1|max:50',
        'start_date' => 'required|date|after:yesterday',
        'end_date' => 'required|date|after:start_date',
        'event_id' => 'required',
        'theater_id' => 'required'
    ];

    public static $updateRules = [
        'stage' => 'min:1|max:50',
        'start_date' => 'date|after:yesterday',
        'end_date' => 'date|after:start_date',
        'event_id' => 'numeric',
        'theater_id' => 'numeric'
    ];
}
