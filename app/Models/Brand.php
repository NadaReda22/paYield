<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Category;
use App\Models\Product;


class Brand extends Model
{
    use Sluggable;

protected $guarded=[];

public function sluggable(): array
{
    return [
        'brand_slug' => [
            'source' => 'brand_name'
        ]
    ];
}

public function categories()
{
    return $this->hasMany(Category::class);
}

public function products()
{
    return $this->belongsToMany(Product::class ,'brand_product')->withTimestamps();
    // This tells Laravel to automatically update created_at and updated_at when using sync(). in many2many
    //bc its not handled itself
}


}
