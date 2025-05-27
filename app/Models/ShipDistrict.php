<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ShipState;
use App\models\ShipDivision;

class ShipDistrict extends Model
{
    protected $guarded=[];

    public function division()
    {
        return $this->belongsTo(ShipDivision::class, 'ship_division_id', 'id');
    }

    
    public function states()
    {
        return $this->hasMany(ShipState::class,'ship_district_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
