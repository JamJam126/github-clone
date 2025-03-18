<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    //
    protected $fillable = [
        'user_id', 
        'repo_id',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repo()
    {
        return $this->belongsTo(Repo::class, 'repo_id');
    }
}
