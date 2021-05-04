<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'material_id'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function material(){
        return $this->belongsTo(Material::class);
    }
}
