<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    //
    protected $fillable = [
        'name', 
        'repo_id', 
        'parent_id',
    ];

    public function repo()
    {
        $this->belongsTo(Repo::class, 'repo_id');
    }

    public function folder()
    {
        $this->belongsTo(Folder::class, 'parent_id');
    }

}
