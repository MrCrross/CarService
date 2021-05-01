<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' ,
        'salary'
    ];

    public function workers(){
        return $this->hasMany(Worker::class);
    }

    public function works(){
        return $this->hasMany(WorkHasPost::class);
    }

    public function contracts(){
        return $this->hasMany(WorkerContract::class);
    }
}
