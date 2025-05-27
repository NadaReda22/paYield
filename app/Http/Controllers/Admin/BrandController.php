<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class BrandController extends Controller
{
   public function index(): View
   {
    $brands=Brand::all();
   return view('admin.brand.all_brand',compact(['brands']));
   }

   public function create(): View
   {
    $brands=Brand::all();
    return view('admin.brand.add_brand',compact(['brands']));
   }



   public function store(Request $request): RedirectResponse
   {
    $request->validate(
        [
            'brand_name'=>'required|string|unique:brands|max:255',
            'brand_image'=>'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]
        );
    $brand=new Brand();

     $brand->brand_name=$request->brand_name;
     
     if($request->hasFile('brand_image'))
     {
        $fileName=time(). '.' . $request->brand_image->extension();
        $request->brand_image->move(public_path('uploads/brands/'),$fileName);
        $brand->brand_image='uploads/brands/'.$fileName;
     }
     $brand->save();

    return redirect()->route('brand.index')->with('success','Brand created successfully');
   }




   public function edit($id)
   {
       $brand = Brand::findOrFail($id);
       return view('admin.brand.edit_brand', compact('brand'));
   }





   public function update(Request $request,$id): RedirectResponse
   {
     $brand=Brand::findOrFail($id);
    $request->validate(
        [
      'brand_name' => 'required|max:255|unique:brands,brand_name,' . $id,//the id to make it unique when search in his column
        'brand_image'=>'image|mimes:jpeg,png,jpg,gif|max:2048'. $id,
        ]
        );
   

     $brand->brand_name=$request->brand_name;
     
     if($request->hasFile('brand_image'))
     {
        $fileName=time(). '.' . $request->brand_image->extension();
        $request->brand_image->move(public_path('uploads/brands/'),$fileName);
        $brand->brand_image='uploads/brands/'.$fileName;
     }

     $brand->save();

    return redirect()->route('brand.index')->with('success','Brand created successfully');
   }

   public function destroy($id)
   {
       $brand = Brand::findOrFail($id);
       if ($brand->brand_image && file_exists(public_path($brand->brand_image))) {
           unlink(public_path($brand->brand_image));
       }
       $brand->delete();
       
       return redirect()->route('brand.index')->with('success', 'Brand deleted successfully.');
   }
}
