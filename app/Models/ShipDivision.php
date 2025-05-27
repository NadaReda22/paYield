<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ShipState;
use App\Models\ShipDistrict;

class ShipDivision extends Model
{
    protected $guarded=[];

    public function states()
    {
        return $this->hasMany(ShipState::class,'ship_division_id', 'id');
    }

    
    public function districts()
    {
        return $this->hasMany(ShipDistrict::class, 'ship_division_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
