<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Coupon extends Model
{
    use Sluggable;
   protected $guarded=[];


   public function sluggable(): array
   {
       return [
           'coupon_slug' => [
               'source' => 'coupon_name'
           ]
       ];
   }
}
