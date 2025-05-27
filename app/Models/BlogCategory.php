<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use App\Models\BlogPost;

class BlogCategory extends Model
{
    use Sluggable;

    protected $guarded=[];

    /***
     * 
     * Sluggable
     */
 public function sluggable(): array
 {
    return [
        'blog_category_slug'=>[
            'source'=>'blog_category_name',
        ]];
 }

 public function posts()
 {
    return $this->hasMany(BlogPost::class);
 }

}
