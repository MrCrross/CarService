<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration',
        'completed'
    ];

    public function compositions(){
        return $this->hasMany(OrderComposition::class);
    }

    public function materials(){
        return $this->hasMany(OrderMaterial::class);
    }
}
