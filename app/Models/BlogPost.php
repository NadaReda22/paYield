<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\BlogCategory;

class BlogPost extends Model
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
        'post_slug'=>[
            'source'=>'post_title',
        ]];
 }


 public function BlogCategory()
 {
    return $this->belongsTo(BlogCategory::class);
 }
}
