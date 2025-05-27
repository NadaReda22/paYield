<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VendorRequestNotification;

class VendorDashboardController extends Controller
{
  
    public function index()
    {
        $vendor = Auth::id();
    
        // Total price (price * quantity)
        $total_price = OrderItem::where('vendor_id', $vendor)
            ->selectRaw('SUM(price * qty) as total_price')
            ->value('total_price');
         
    
        // Total distinct orders
        $total_order = OrderItem::where('vendor_id', $vendor)
            ->distinct('order_id')
            ->count('order_id');
    
        // Total distinct users/clients who made orders for this vendor
        $total_user = OrderItem::where('vendor_id', $vendor)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->distinct('orders.user_id')
            ->count('orders.user_id');
    
        // All order items with relations
        $all_order = OrderItem::with('product', 'order.user') // assuming user is related to order
            ->where('vendor_id', $vendor)
            ->get();
            // dd(compact('total_price', 'total_order', 'total_user', 'all_order'));

    
        return view('vendor.index', compact('total_price', 'total_order', 'total_user', 'all_order'));
    }
    


    //Show Vendor Profile 

    public function VendorProfile(){
        $id = Auth::user()->id;
        $vendor_data = User::find($id);
        return view('vendor.vendor_profile',compact('vendor_data'));
    }


    //Update Vendor Profile

    public function VendorProfileUpdate(Request $request)
    {
        $id = Auth::user()->id;
        $vendor_data = User::findOrFail($id);
    
        $validatedData = $request->validate([
            // 'name' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $vendor_data->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'vendor_join_date' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'vendor_info' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    

        if ($request->hasFile('photo')) {
            $fileName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('uploads/vendors/'), $fileName);
            $validatedData['photo'] = 'uploads/vendors/' . $fileName;
        }
    
        $vendor_data->update($validatedData);
    
        return redirect()->back()->with('success', 'Profile Updated Successfully');
    }
    
    

    //Vendor Change Password

    public function VendorChangePassword(){
        
        return view('vendor.vendor_change_password');
    }

    // Update Vendor Password 

    public function VendorUpdatePassword(Request $request)
    {
        $request->validate([
            'old_password'=>'required',
            'new_password'=>'required|confirmed',
        ]);

        if(!Hash::check($request->old_password , Auth::user()->password))
        {
            return back()->with('error',"Old Password Doesn't Match");
        }
        User::whereId(Auth::user()->id)->update(
            [
                'password'=>Hash::make($request->new_password)
            ]
            );

            return back()->with('success', "Password Change Successfully");
    }

}
