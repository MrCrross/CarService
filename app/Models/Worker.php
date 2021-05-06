<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'first_name' ,
        'last_name' ,
        'father_name' ,
        'phone'
    ];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function contracts(){
        return $this->hasMany(WorkerContract::class);
    }

    public function orders(){
        return $this->hasMany(OrderComposition::class)->latest()->select('order_id','worker_id')->groupBy('order_id','worker_id');
    }

}
