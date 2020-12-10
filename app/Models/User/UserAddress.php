<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
  protected $table = 'wl_user_address';

  public function getUser(){
    return $this->hasOne('App\User','id','user');
  }
}
