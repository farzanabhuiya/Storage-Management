<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    
    protected $fillable = [
        'user_id',
        'type',
        'name',
        'is_locked',
        'lock_pin',
        'is_favorite',
    ];

    protected $hidden = ['lock_pin'];
}
