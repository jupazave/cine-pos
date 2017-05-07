<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $createRules = [
        'username' => 'required|min:5|max:35|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:3|max:20|confirmed',
        'first_name' => 'alpha|min:2|max:40',
        'last_name' => 'alpha|min:2|max:40',
        'zipcode' => 'required|numeric',
        'city' => 'required|alphadash',
        'country' => 'required|alphadash'
    ];

    public static $updateRules = [
        'username' => 'min:5|max:35|unique:users',
        'email' => 'email|unique:users',
        'first_name' => 'alpha|min:2|max:40',
        'last_name' => 'alpha|min:2|max:40',
        'zipcode' => 'numeric',
        'city' => 'alphadash',
        'country' => 'alphadash'
    ];
}
