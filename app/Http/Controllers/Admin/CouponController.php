<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
class CouponController extends Controller
{
    public function couponAllData()
    {
     $coupons =Coupon::all();
     return view('admin.coupon.coupon_all',compact('coupons'));
    }
 
 
    public function couponCreate()
    {
        $coupon =Coupon::all();
     return view('admin.coupon.coupon_add',compact('coupon'));
    }
 
 
    public function couponStore(Request $request)
 {
     $validatedData = $request->validate([
         'coupon_name' => 'required|string|max:255|unique:coupons,coupon_name',
         'coupon_discount' => 'required|integer|min:1|max:100',
         'coupon_validity' => 'required|date|after_or_equal:today',
        //  'status' => 'required|in:0,1',
     ]);
 
 
 
     // Store the Admin record
   Coupon::create($validatedData); // FIXED
 
     return redirect()->route('coupons.all')->with('success', 'Coupon created successfully');
 }
 
 
    public function couponEdit($id)
    {
     $coupon =Coupon::findOrfail($id);
     return view('admin.coupon.coupon_edit',compact('coupon'));
    }
 
 
    public function couponUpdate(Request $request, $id)
    {
        $coupon =Coupon::findOrFail($id);
    
        $validatedData = $request->validate([
            'coupon_name' => 'required|string|max:255|unique:coupons,coupon_name,'.$id,
            'coupon_discount' => 'required|integer|min:1|max:100',
            'coupon_validity' => 'required|date|after_or_equal:today',
            // 'status' => 'required|in:0,1',
        ]);
    
    
        // Force fill and save
        $coupon->update($validatedData);
    
        return redirect()->route('coupons.all')->with('success', 'Coupon updated successfully');
    }
    
    
    
 
 /**
  * Admin Delete
  */
    public function couponDestroy($id)
    {
     $coupon=Coupon::findOrFail($id);
 
     $coupon->delete();
 
     return redirect()->route('coupons.all')->with('success', 'Coupon deleted successfully');
    }
}
