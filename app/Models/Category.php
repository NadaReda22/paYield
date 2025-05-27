<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Brand;
use App\Models\Product;
class Category extends Model
{
    use Sluggable;

    protected $guarded=[];
    
    public function sluggable(): array
    {
        return [
            'category_slug' => [
                'source' => 'category_name'
            ]
        ];
    }
    
    //Get the brand of the product
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    //self refrencing relationship between category and subcategory
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }

    //Get all subcategories of category
    public function subCategories()
    {
        return $this->hasMany(Category::class,'parent_id');
    }

    //Get all products of category
    public function products()
    {
        return $this->hasMany(Product::class,'category_id');
    }
}
