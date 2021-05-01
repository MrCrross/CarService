<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class WorkHasPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'work_id'
    ];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function work(){
        return $this->belongsTo(Work::class);
    }
}
