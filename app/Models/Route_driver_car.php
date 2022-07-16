<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route_driver_car extends Model
{
    use HasFactory;


    public function driver_name()
    {
        return $this->hasMany(User::class,'id','driver_id');
    }

    public function car_name()
    {
        return $this->hasMany(Carriage::class,'id','car_id');
    }
}
