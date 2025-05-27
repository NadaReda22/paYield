<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded=[];

    /**
     * Order Has many Items
     */

     public function Order()
     {
        return $this->belongsTo(Order::class);
     }

     /**
     * Item Belongs to Vendor
     */

     public function vendor()
     {
         return $this->belongsTo(User::class, 'vendor_id');
     }
     
   
     /**
     * Item Belongs to Product
     */

     public function product()
     {
       return $this->belongsTo(Product::class);
     }
     

    /**
     * Item Belongs to User
     */

     public function user()
     {
       return $this->belongsTo(User::class);
     }


}
