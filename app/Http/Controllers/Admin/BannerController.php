<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function bannerAllData()
    {
     $banners =Banner::all();
     return view('admin.banner.banner_all',compact('banners'));
    }
 
 
    public function bannerCreate()
    {
        $banner =Banner::all();
     return view('admin.banner.banner_add',compact('banner'));
    }
 
 
    public function bannerStore(Request $request)
 {
     $validatedData = $request->validate([
         'banner_url' => 'string|required|max:255|unique:banners,banner_url',
         'banner_title' => 'required|string|max:255',
         'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
     ]);
 
 
 
     // Update User Photo
     if ($request->hasFile('image')) {
         $fileName = time() . '.' . $request->image->extension();
         $request->image->move(public_path('uploads/banners/'), $fileName);
         $validatedData['image'] = 'uploads/banners/' . $fileName;
     }
 
     // Store the Admin record
   Banner::create($validatedData); // FIXED
 
     return redirect()->route('banners.all')->with('success', 'Banner created successfully');
 }
 
 
    public function bannerEdit($id)
    {
     $banner =Banner::findOrfail($id);
     return view('admin.banner.banner_edit',compact('banner'));
    }
 
 
    public function bannerUpdate(Request $request, $id)
    {
        $banner =Banner::findOrFail($id);
    
        $validatedData = $request->validate([
            'banner_url' => 'string|required|max:255|unique:banners,banner_url,'.$id,
            'banner_title' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
    
        // Update User Photo
        if ($request->hasFile('image')) {
            if (!empty($banner->image) && file_exists(public_path($banner->image))) {
                unlink(public_path($banner->image));
            }
    
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/banners/'), $fileName);
            $validatedData['image'] = 'uploads/banners/' . $fileName;
        }
    
        // Force fill and save
        $banner->update($validatedData);
    
        return redirect()->route('banners.all')->with('success', 'Banner updated successfully');
    }
    
    
    
 
 /**
  * Admin Delete
  */
    public function bannerDestroy($id)
    {
     $banner=Banner::findOrFail($id);
 
     //check if file exists to unlink and delete
     if($banner->image && file_exists(public_path($banner->image)))
     {
         unlink(public_path($banner->image));
     }
 
     $banner->delete();
 
     return redirect()->route('banners.all')->with('success', 'Banner deleted successfully');
    }
}
