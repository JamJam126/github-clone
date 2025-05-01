<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $fillable = [
        'content', 
        'file_id',
        'prev_id',

    ];

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function prevContent()
    {
        return $this->belongsTo(Content::class, 'prev_id');
    }

}
