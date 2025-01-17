<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $fillable = [
        'name', 
        'commit_id', 
        'repo_id', 
        'previous_version_id',
        'file_size',
        'file_path',
        'is_latest'
    ];

    public function repo() 
    {
        return $this->belongsTo(Repo::class, 'repo_id');
    }

    public function commit() 
    {
        return $this->belongsTo(Commit::class, 'commit_id');
    }

    public function file() 
    {
        return $this->hasOne(File::class);    
    }
}
