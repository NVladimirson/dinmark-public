<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $table = 'b2b_queues';

    protected $fillable = [
        'name',
        'entity_id',
        'position',
        'step',
    ];
}
