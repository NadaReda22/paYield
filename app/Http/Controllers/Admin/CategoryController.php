<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;


class CategoryController extends Controller
{

  public function index(): View
       {
        $categories=Category::all();
       return view('admin.category.all_category',compact(['categories']));
       }
    
       public function create(): View
       {
        $categories=Category::all();
        return view('admin.category.add_category',compact(['categories']));
       }
    
    
    
       public function store(Request $request): RedirectResponse
       {
        $request->validate(
            [
                'category_name'=>'required|string|unique:categories|max:255' ,
                'category_image'=>'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]
            );
        $category=new Category();
    
         $category->category_name=$request->category_name;
         
         if($request->hasFile('category_image'))
         {
            $fileName=time(). '.' . $request->category_image->extension();
            $request->category_image->move(public_path('uploads/categories/'),$fileName);
            $category->category_image='uploads/categories/'.$fileName;
         }
         $category->save();
    
        return redirect()->route('category.index')->with('success','Category created successfully');
       }
    
    
    
    
       public function edit($id)
       {
           $category = Category::findOrFail($id);
           return view('admin.category.edit_category', compact('category'));
       }
    
    
    
    
    
       public function update(Request $request,$id): RedirectResponse
       {
         $category=Category::findOrFail($id);
        $request->validate(
            [
          'category_name' => 'required|max:255|unique:categories,category_name,' . $id,//the id to make it unique when search in his column
            'category_image'=>'image|mimes:jpeg,png,jpg,gif|max:2048'. $id,
            ]
            );
       
    
         $category->category_name=$request->category_name;
         
         if($request->hasFile('category_image'))
         {
            $fileName=time(). '.' . $request->category_image->extension();
            $request->category_image->move(public_path('uploads/categories/'),$fileName);
            $category->category_image='uploads/categories/'.$fileName;
         }
    
         $category->save();
    
        return redirect()->route('category.index')->with('success','Category created successfully');
       }
    
       public function destroy($id)
       {
           $category = Category::findOrFail($id);
           if ($category->category_image && file_exists(public_path($category->category_image))) {
               unlink(public_path($category->category_image));
           }
           $category->delete();
           
           return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
       }
    }
    
