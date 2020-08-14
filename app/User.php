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
        'name', 'email', 'password', 'last_login', 'company'
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
    	return $this->hasOne('App\Models\Company\Company','id', 'company');
	}

    public function role(){
    	return $this->hasOne('App\Models\User\Role','id', 'type');
	}

    public function price(){
    	return $this->hasOne('App\Models\User\UserPrice','user_type', 'type');
	}

    public function getStatus(){
    	return $this->hasOne('App\Models\User\UserStatus', 'id','status');
	}

	public function logs(){
    	return $this->hasMany('App\Models\Log\Log','user');
	}

	public function wishlists(){
		return $this->hasMany('App\Models\Wishlist\LikeGroup','user_id');
	}
}
