<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'price',
        'registration',
        'completed'
    ];

    public function car(){
        return $this->belongsTo(Car::class);
    }

    public function compositions(){
        return $this->hasMany(OrderComposition::class);
    }

    public function materials(){
        return $this->hasMany(OrderMaterial::class);
    }
}
