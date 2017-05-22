<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function schedules(){
        return $this->belongsToMany('App\Event', 'schedules');
    }
    //
    protected $guarded = [];

    public static $createRules = [
        'name' => 'required|min:3|max:35',
        'description' => 'min:5',
        'phone' => 'required|min:7',
        'address' => 'required|min:5|max:50',
        'zip_code' => 'required|min:5|max:10',
        'city' => 'required|min:2|max:50',
        'country' => 'required|min:2|max:50',
        'email' => 'email',
        'facebook' => 'url',
        'instagram' => 'url',
        'twitter' => 'url',
        'webpage' => 'url',
        'profile_picture' => 'url'
    ];

    public static $updateRules = [
        'name' => 'min:3|max:35',
        'description' => 'min:5',
        'phone' => 'min:7',
        'address' => 'min:5|max:50',
        'zip_code' => 'min:5|max:10',
        'city' => 'min:2|max:50',
        'country' => 'min:2|max:50',
        'email' => 'email',
        'facebook' => 'url',
        'instagram' => 'url',
        'twitter' => 'url',
        'webpage' => 'url',
        'profile_picture' => 'url'
    ];
}
