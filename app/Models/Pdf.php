<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $fillable = [
        'user_id', 'path', 'filename', 'mimetype', 'size_in_kb',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
