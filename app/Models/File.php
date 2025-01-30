<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $fillable = [
        'name', 
        'previous_version_id',
        'folder_id',
        'file_size',
        'file_path',
        'is_latest',
        'repo_id',
    ];

    public function folder() 
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    public function repo()
    {
        return $this->belongsTo(Repo::class, 'repo_id');
    }

    // public function commit() 
    // {
    //     return $this->belongsTo(Commit::class, 'commit_id');
    // }

    // public function file() 
    // {
    //     return $this->hasOne(File::class);    
    // }
}
