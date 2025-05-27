<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ShipDivision;
use App\Models\ShipDistrict;

class ShipState extends Model
{
    protected $guarded=[];


    public function division(){
        return $this->belongsTo(ShipDivision::class,'ship_division_id', 'id');  
    }

    public function district(){
        return $this->belongsTo(ShipDistrict::class,'ship_district_id', 'id');  
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
