<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Models\MultiImg;
// use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\DB ;

class Product extends Model
{
    use Sluggable;
   protected $guarded=[];
   
   public function sluggable(): array
   {
       return [
           'product_slug' => [
               'source' => 'product_name'
           ]
       ];
   }
        //Get brands of product
        public function brands()
         {
             return $this->belongsToMany(Brand::class ,'brand_product')->withTimestamps();
         }

         //Get subcategory of product
        public function subcategory()
         {
             return $this->belongsTo(Category::class,'subcategory_id');
         }
          //Get category of product
         public function category()
         {
             return $this->belongsTo(Category::class,'category_id');
         }
        //Get active vendor of product
        //  public function vendor()
        //  {
        //     $ActiveVendors= DB::table('users')
        //     ->select('name')
        //     ->where('role','vendor')
        //     ->Where('status','active')
        //     ->get();

        //     return $ActiveVendors;
        //  }

            //Get active vendor of product
            public function vendor()
            {
                return $this->belongsTo(User::class, 'user_id')->where([
                    'role' => 'vendor',
                    'status' => 'active',
                ])->withDefault([
                    'name' => 'Unknown Vendor'
                ]);
            }
            
         //Get multi images of product
         public function multiimages()
         {
            return $this->hasMany(MultiImg::class);
         }

        //Get review of product
        public function reviews()
        {
            return $this->hasMany(Review::class);
        }
}
