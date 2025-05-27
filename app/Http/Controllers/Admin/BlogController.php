<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogController extends Controller
{

    /**
     * 
     * Get Blog Category
     */
    public function categoryAllData()
    {
        $blogCategories=BlogCategory::all();
        return view('admin.blog.blogcategory_all',compact('blogCategories'));
    }
  
    /**
     * 
     * Add Blog Category
     */
    public function categoryCreate()
    {
        $blogCategory=BlogCategory::all();
        return view('admin.blog.blogcategory_add',compact('blogCategory'));
    }

    /**
     * 
     * Store Blog Category
     */

    public function categoryStore(Request $request)
    {
        $validatedData =$request->validate([
            'blog_category_name'=>'required|string|max:255|unique:blog_categories,blog_category_name',
        ]);

       BlogCategory::create($validatedData);

        return redirect()->route('blog.category.all')->with('success','Blog Category added Successfully');
    }
   

    /**
     * Edit Blog Category
     */

    public function categoryEdit($id)
    {
        $blogCategory=BlogCategory::findOrFail($id);
        return view('admin.blog.blogcategory_edit',compact('blogCategory'));
    }
    

    /**
     * 
     * Update Blog Category
     */

    public function categoryUpdate(Request $request ,$id)
    {      
        $blogCategory=BlogCategory::findOrFail($id);

        $validatedData =$request->validate([
            'blog_category_name'=>'required|string|max:255|unique:blog_categories,blog_category_name,' .$id,
        ]);

        $blogCategory->update($validatedData);

        return redirect()->route('blog.category.all')->with('success','Blog Category Updated Successfully');
    }


    /**
     * 
     * Delete Blog Category
     */

    public function categoryDestroy($id)
    {
        $blogCategory=BlogCategory::findOrFail($id);
   
        $blogCategory->delete();

        return redirect()->route('blog.category.all')->with('success','Blog Category deleted successfully');


    }


    /**********************************************************************************************
     ********************************** Blog Posts **********************************************
     **********************************************************************************************/

    public function postAllData()
    {
        $blogPosts=BlogPost::with('blogCategory')->get();
        return view('admin.blog.blogpost_all',compact('blogPosts'));
    }


    public function postCreate()
    {
        $blogCategories=BlogCategory::all();
        return view('admin.blog.blogpost_add',compact('blogCategories'));
    }



    public function postStore(Request $request)
    {
        $validatedData =$request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'post_title' => 'required|string|max:255',
            'post_short_desc' => 'required|string|max:500',
            'post_long_desc' => 'nullable|string',
            'post_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
         
        if($request->hasFile('post_image'))
        {
            $fileName=time(). '.'.$request->post_image->extension();
            $request->post_image->move(public_path('uploads/Blogs/'),$fileName);
            $validatedData['post_image']='uploads/Blogs/'.$fileName;
        }
        BlogPost::create($validatedData);

        return redirect()->route('blog.post.all')->with('success','Blog Category added Successfully');
    }




    public function postEdit($id)
    {
        $blogPost=BlogPost::findOrFail($id);
        $blogCategories=BlogCategory::all();
        return view('admin.blog.blogpost_edit',compact(['blogPost','blogCategories']));
    }



    public function postUpdate(Request $request,$id)
    {

        $blogPost=BlogPost::findOrFail($id);

        $validatedData =$request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'post_title' => 'required|string|max:255',
            'post_short_desc' => 'required|string|max:500',
            'post_long_desc' => 'nullable|string',
            'post_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
         
        if($request->hasFile('post_image'))
        {    
            if(!empty( $blogPost->post_image) && file_exists(public_path($blogPost->post_image)) )
            {
              unlink(public_path($blogPost->post_image));
            }

            $fileName=time(). '.'.$request->post_image->extension();
            $request->post_image->move(public_path('uploads/Blogs/'),$fileName);
            $validatedData['post_image']='uploads/Blogs/'.$fileName;
        }
        $blogPost->update($validatedData);

        return redirect()->route('blog.post.all')->with('success','Blog Post updated Successfully');
    }




    public function postDestroy($id)
    {
        $blogPost=BlogPost::findOrFail($id);

        if(!empty( $blogPost->post_image) && file_exists(public_path($blogPost->post_image)) )
        {
          unlink(public_path($blogPost->post_image));
        }
        
        $blogPost->delete();

        return redirect()->route('blog.post.all')->with('success','Blog Post deleted successfully');


    }


    /**
     * For Frontend
     * 
     */
    public function AllBlog(){
        $blogcategoryies = BlogCategory::latest()->get();
        $blogpost = BlogPost::latest()->get();
        return view('frontend.blog.home_blog',compact('blogcategoryies','blogpost'));
    }// End Method 


    public function BlogDetails($id,$slug){
        $blogcategoryies = BlogCategory::latest()->get();
        $blogdetails = BlogPost::findOrFail($id);
        $breadcat = BlogCategory::where('id',$id)->get();
        return view('frontend.blog.blog_details',compact('blogcategoryies','blogdetails','breadcat'));

    }// End Method


    public function BlogPostCategory($id,$slug){

        $blogcategoryies = BlogCategory::latest()->get();
        $blogpost = BlogPost::where('blog_category_id',$id)->get();
        $breadcat = BlogCategory::where('id',$id)->get();
        return view('frontend.blog.category_post',compact('blogcategoryies','blogpost','breadcat'));

    }// End Method 

}


