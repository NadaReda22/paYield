<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $guarded=[];


   
public function User(){
return $this->belongsTo(User::class);
}

   public function division(){
    return $this->belongsTo(ShipDivision::class,'ship_division_id', 'id');  
}

public function district(){
    return $this->belongsTo(ShipDistrict::class,'ship_district_id', 'id');  
}

   public function state(){
    return $this->belongsTo(ShipState::class,'ship_state_id', 'id');  
}

}
