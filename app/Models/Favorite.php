<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id', 'note_id', 'image_id', 'pdf_id', 'type', 'item_id'
    ];

    public function note()
{
    return $this->belongsTo(Note::class);
}

public function image()
{
    return $this->belongsTo(Image::class);
}

public function pdf()
{
    return $this->belongsTo(Pdf::class);
}
}
