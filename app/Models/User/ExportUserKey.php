<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class ExportUserKey extends Model
{
    protected $table = 'export_user_key';
    public $timestamps = false;

    protected $fillable = [
        'user', 'groups', 'date_add', 'language', 'new'
    ];
}
