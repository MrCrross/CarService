<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price'
    ];

    public function posts(){
        return $this->hasMany(WorkHasPost::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
