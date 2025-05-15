<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
     protected $fillable = ['name', 'user_id', 'parent_folder_id','size_in_kb'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_folder_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_folder_id');
    }
}
