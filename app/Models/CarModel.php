<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'firm_id' ,
        'name' ,
        'year_release'
    ];

    public function firm()
    {
        return $this->belongsTo(CarFirm::class);
    }
}
