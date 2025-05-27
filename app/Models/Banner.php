<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;



class Banner extends Model
{
    use Sluggable;
   protected $guarded=[];


   public function sluggable(): array
   {
       return [
           'banner_slug' => [
               'source' => 'banner_title'
           ]
       ];
   }
}
