<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Slider extends Model 
{
    use Sluggable;
   protected $guarded=[];


   public function sluggable(): array
   {
       return [
           'slider_slug' => [
               'source' => 'slider_title'
           ]
       ];
   }
}
