<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
class SliderController extends Controller
{
    public function sliderAllData()
    {
     $sliders =Slider::all();
     return view('admin.slider.slider_all',compact('sliders'));
    }
 
 
    public function sliderCreate()
    {
        $slider =Slider::all();
     return view('admin.slider.slider_add',compact('slider'));
    }
 
 
    public function sliderStore(Request $request)
 {
     $validatedData = $request->validate([
         'slider_url' => 'string|required|max:255|unique:sliders,slider_url',
         'slider_title' => 'required|string|max:255',
         'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
     ]);
 
 
 
     // Update User Photo
     if ($request->hasFile('image')) {
         $fileName = time() . '.' . $request->image->extension();
         $request->image->move(public_path('uploads/sliders/'), $fileName);
         $validatedData['image'] = 'uploads/sliders/' . $fileName;
     }
 
     // Store the Admin record
   Slider::create($validatedData); // FIXED
 
     return redirect()->route('sliders.all')->with('success', 'Slider created successfully');
 }
 
 
    public function sliderEdit($id)
    {
     $slider =Slider::findOrfail($id);
     return view('admin.slider.slider_edit',compact('slider'));
    }
 
 
    public function sliderUpdate(Request $request, $id)
    {
        $slider =Slider::findOrFail($id);
    
        $validatedData = $request->validate([
            'slider_url' => 'string|required|max:255|unique:sliders,slider_url,'.$id,
            'slider_title' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
    
        // Update User Photo
        if ($request->hasFile('image')) {
            if (!empty($slider->image) && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }
    
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/sliders/'), $fileName);
            $validatedData['image'] = 'uploads/sliders/' . $fileName;
        }
    
        // Force fill and save
        $slider->update($validatedData);
    
        return redirect()->route('sliders.all')->with('success', 'Slider updated successfully');
    }
    
    
    
 
 /**
  * Slider Delete
  */
    public function sliderDestroy($id)
    {
     $slider=Slider::findOrFail($id);
 
     //check if file exists to unlink and delete
     if($slider->image && file_exists(public_path($slider->image)))
     {
         unlink(public_path($slider->image));
     }
 
     $slider->delete();
 
     return redirect()->route('sliders.all')->with('success', 'Slider deleted successfully');
    }
}
