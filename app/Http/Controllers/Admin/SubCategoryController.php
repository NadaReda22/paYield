<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;


class SubCategoryController extends Controller
{

  public function index(): View
       {
        $categories=Category::with('subCategories')->get();
       return view('admin.subcategory.all_subcategory',compact(['categories']));
       }
    
       public function create(): View
       {
        $categories = Category::with('subCategories')->get();
        return view('admin.subcategory.add_subcategory',compact(['categories']));
       }
    

    
       public function store(Request $request): RedirectResponse
       {
           $request->validate([
               'subcategory_name' => 'required|string|max:255|unique:categories,category_name',
               'subcategory_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
               'parent_id' => 'nullable|exists:categories,id' // Validate parent id should exists in category in col category.id
           ]);
       
        //    dd($request);
           $category = new Category();
           $category->category_name = $request->subcategory_name;
           $category->parent_id = $request->parent_id; // Set parent_id
       
           if ($request->hasFile('subcategory_image')) {
               $fileName = time() . '.' . $request->subcategory_image->extension();
               $request->subcategory_image->move(public_path('uploads/subcategories/'), $fileName);
               $category->category_image = 'uploads/subcategories/' . $fileName;
           }
       
           $category->save();
       
           return redirect()->route('subcategory.index')->with('success', 'Subcategory created successfully');
       }
       
    
    
    
    
       public function edit($id)
       {
           $category = Category::findOrFail($id);
           $categories=Category::all();
           return view('admin.subcategory.edit_subcategory', compact(['category','categories']));
       }
    
    
    
    
    
       public function update(Request $request, $id): RedirectResponse
       {
           $category = Category::findOrFail($id);
           $request->validate([
               'subcategory_name' => 'required|max:255|unique:categories,category_name,' . $id, // Fix the unique validation
               'subcategory_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
               'parent_id' => 'nullable|exists:categories,id',
           ]);
       
           $category->category_name = $request->subcategory_name;
           $category->parent_id = $request->parent_id;
       
           if ($request->hasFile('subcategory_image')) {
               $fileName = time() . '.' . $request->subcategory_image->extension();
               $request->subcategory_image->move(public_path('uploads/subcategories/'), $fileName);
               $category->category_image = 'uploads/subcategories/' . $fileName;
           }
       
           $category->save();
       
           return redirect()->route('subcategory.index')->with('success', 'SubCategory updated successfully');
       }
       
    
       public function destroy($id)
       {
           $category = Category::findOrFail($id);
           if ($category->category_image && file_exists(public_path($category->category_image))) {
               unlink(public_path($category->category_image));
           }
           $category->delete();
           
           return redirect()->route('subcategory.index')->with('success', 'Brand deleted successfully.');
       }
    }
    
