<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name' ,
        'last_name' ,
        'father_name' ,
        'phone'
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
