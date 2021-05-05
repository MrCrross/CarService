<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderComposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'worker_id',
        'work_id'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function worker(){
        return $this->belongsTo(Worker::class);
    }

    public function work(){
        return $this->belongsTo(Work::class);
    }

}
