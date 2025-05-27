<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Wishlist extends Model
{
   protected $guarded=[];


   public function user()
   {
    return $this->belongsTo(User::class);
   }

   public function product()
   {
    return $this->belongsTo(Product::class);
   }
}
