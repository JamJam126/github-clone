<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    //
    protected $fillable = [
        'message', 
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

    public function file()
    {
        return $this->hasMany(File::class);
    }
}
