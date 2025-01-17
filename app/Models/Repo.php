<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repo extends Model
{
    //
    use HasFactory;

    // protected $fillable = [
    //     'title',
    //     'content',
    //     'user_id',
    // ];
    protected $fillable = [
        'name', 
        'description', 
        'visibility', 
        'user_id'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function file()
    {
        return $this->hasMany(File::class);
    }

    public function commit()
    {
        return $this->hasMany(Commit::class);
    }
}
