<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'wl_users';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function info(){
    	return $this->hasMany('App\Models\User\UserInfo','user');
	}

    public function dataChangeRequest(){
    	return $this->hasMany('App\Models\User\UserDataChangeRequest','user_id');
	}


    public function getCompany(){
    	return $this->hasOne('App\Models\User\Company','id', 'company');
	}
}
