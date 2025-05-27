<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminApprovalNotification;
use App\Notifications\VendorRequestNotification;

class VendorController extends Controller
{
   public function ActiveVendor()
   {
        $ActiveVendors= User::where([
            'role'=>'vendor',
            'status'=>'active'])->get();

        return view('admin/vendor/active_vendor', compact(['ActiveVendors']));

   }

   public function InactiveVendor()
   {
        $InactiveVendors= User::where([
            'role'=>'vendor',
            'status'=>'inactive'])->get();

        return view('admin/vendor/inactive_vendor', compact(['InactiveVendors']));

   }

   public function activeVendorDetails($id)
   {
       $activeVendorDetails= User::findOrFail($id);

    return view('admin/vendor/active_vendordetails', compact(['activeVendorDetails']));
   }

   public function inactiveVendorDetails($id)
   {
        $inactiveVendorDetails= User::findOrFail($id);

        return view('admin/vendor/inactive_vendordetails', compact(['inactiveVendorDetails']));

   }

   public function activeVendorApprove($id)
   {
       $activeVendorApprove= User::findOrFail($id)->update(['status'=>'active']);
       $vendor=User::where('id',$id)->firstOrFail();
       $vendors = User::where('role','vendor')->get();
       Notification::send($vendors, new AdminApprovalNotification($vendor));
       
    return redirect()->route('ActiveVendor.all')->with('success','The vendor was activated successfully');
   }
   public function inActiveVendorApprove($id)
   {
    $inActiveVendorApprove= User::findOrFail($id)->update(['status'=>'inactive']);
       
    return redirect()->route('InactiveVendor.all')->with('success','The vendor was inactivated successfully');
   }

   public function becomeVendor(){
    return view('vendor.become_vendor');

}  



public function VendorRegister(Request $request)
{
    $vuser = User::where('role', 'admin')->get();

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed'],
    ]);

    // Create the vendor user
    $user = User::create([ 
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'phone' => $request->phone,
        'vendor_join_date' => $request->vendor_join,
        'password' => Hash::make($request->password),
        'role' => 'vendor',
        'status' => 'inactive',
    ]);

    // ✅ Force login as the new vendor
    Auth::logout(); // Log out previous user
    Auth::login($user); // Log in the new vendor

    // ✅ Trigger email verification
    event(new Registered($user));

    // Optional: Notify admins
    Notification::send($vuser, new VendorRequestNotification($request));

    $notification = [
        'message' => 'Vendor Registered Successfully. Please verify your email.',
        'alert-type' => 'success',
    ];

    return redirect()->route('verification.notice')->with($notification);
}






}
