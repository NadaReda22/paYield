<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MultiImg;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

use Dompdf\Dompdf;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;
class UserController extends Controller
{

/*
**Get Users Data
*/

 public function usersAllData(){
    $users=User::where([
        'role'=>'user',
    ])->get();
   return view('admin.usermanage.user_all_data',compact('users'));
 }

/*
**Edit User's Data
*/

public function userEdit($id)
{
    $user=User::findOrFail($id);

    return view('admin.usermanage.edit_user',compact('user'));
}


/*
**Update User's Data
*/
public function userUpdate(Request $request,$id)
{
    $user=User::findOrFail($id);
$validatedData=$request->validate(
    [
     'name'=>'string|required|max:255',
     'email' => 'required|string|email|max:255|unique:users,email,' . $id,
     'shop_name'=>'string|nullable|max:255',
    //  'password'=>'required|string|min:8|confirmed',
    'username' => 'nullable|string|max:255|unique:users,username,' . $id,
     'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
     'phone' => 'nullable|string|min:10|max:15',
     'address' => 'nullable|string|max:255',
    //  'role' => 'required|in:user,vendor,admin',
    //  'status' => 'required|in:active,inactive',
    //  'lastseen' => 'nullable|date',
    //  'vendor_info' => 'nullable|string',
    //  'vendor_join_date'=>'nullable|digits:4|integer|min:2024|max:'.date('Y'),
    ]
    );

     //Update User Photo

     if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if (!empty($user->photo) && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }
    
        $fileName = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('uploads/users/'), $fileName);
        $validatedData['photo'] = 'uploads/users/' . $fileName;
    }
    $user->update($validatedData);

  
     return redirect()->back()->with('success', 'User updated successfully');

}

/**
 * User Delete
 */
public function userDestroy($id)
{
    $user= User::findOrFail($id);
    if ($user->photo && file_exists(public_path($user->photo))) {
        unlink(public_path($user->photo));
    }
    $user->delete();
    
    return redirect()->route('user.destroy')->with('success', 'User deleted successfully.');
}

   /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', "Old password doesn't match.");
        }

        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with([
            'message' => 'Password updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Show user dashboard.
     */
    public function dashboard()
    {
        $user_data = Auth::user();
        return view('frontend.userdashboard.dashboard', compact('user_data'));
    }

    /**
     * Show user orders.
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->orderByDesc('id')
                       ->get();

        return view('frontend.userdashboard.order', compact('orders'));
    }


    /**
     * Show details of a specific order.
     */
    public function viewOrderDetail($orderId)
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        $orderItem = OrderItem::with('product')
                               ->where('order_id', $orderId)
                               ->orderByDesc('id')
                               ->get();
// dd($orderItem);
        return view('frontend.userdashboard.order_details', compact('order', 'orderItem'));
    }

    /**
     * Generate and download PDF invoice for a specific order.
     */
    public function downloadInvoice($id)
    {
       // Fetch the order with related models, ensuring it belongs to the logged-in user
       $order = Order::with(['division', 'district', 'state', 'user'])
       ->where('id', $id)
       ->where('user_id', Auth::id())
       ->firstOrFail();

// Fetch order items with related models
$orderItem = OrderItem::with(['order', 'product', 'user'])
               ->where('order_id', $id)
               ->get();

// Configure Dompdf options
$options = new Options();
$options->set('tempDir', public_path());
$options->set('chroot', public_path());

// Instantiate Dompdf with options
$dompdf = new Dompdf($options);

// Render the view to HTML
$html = view('frontend.userdashboard.order_invoice', compact('order', 'orderItem'))->render();
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Stream the PDF to the browser
return $dompdf->stream('invoice-' . $id . '.pdf');

}

public function showTrackOrderForm()
{
    return view('frontend.userdashboard.user_track_order');
}

public function trackOrder(Request $request)
{
    $request->validate([
        'code' => 'required|string'
    ]);

    $track = Order::where('invoice_no', $request->code)
                  ->where('user_id', Auth::id())
                  ->first();

    if (!$track) {
        return back()->with([
            'message' => 'Invoice code is invalid.',
            'alert-type' => 'error',
        ]);
    }

    return view('frontend.userdashboard.track_order', compact('track'));
}

    /**
     * Show user account details.
     */
    public function accountDetails()
    {
        $user_data = Auth::user();
        return view('frontend.userdashboard.account_details', compact('user_data'));
    }

    /**
     * Show password settings view.
     */
    public function passwordSettings()
    {
        return view('frontend.userdashboard.password_setting');
    }
    
    
    public function returnOrder(Request $request, $order_id)
{
    $request->validate([
        'return_reason' => 'required|string|max:1000',
    ]);

    $order = Order::where('id', $order_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $order->update([
        'return_date'   => now()->format('d F Y'),
        'return_reason' => $request->return_reason,
        'return_order'  => 1,
    ]);

    return redirect()->back()->with([
        'message'    => 'Return request sent successfully.',
        'alert-type' => 'success',
    ]);
        }

        public function returnOrderPage()
        {
            $orders = Order::where('user_id', Auth::id())
                ->whereNotNull('return_reason')
                ->whereIn('return_order',[1,2])
                ->orderByDesc('id')
                ->get();

            return view('frontend.userdashboard.return_order_view', compact('orders'));
        }




/***
 * 
 * 
 * Vendor
 * 
 */



 public function vendorsAllData(){
    $vendors=User::where(
        [
            'role'=>'vendor',
        ]
    )->get();
   return view('admin.usermanage.vendor_all_data',compact('vendors'));
 }



/*
**Edit User's Data
*/

public function vendorEdit($id)
{
    $vendor=User::findOrFail($id);

    return view('admin.usermanage.edit_vendor',compact('vendor'));
}


/*
**Update User's Data
*/
public function vendorUpdate(Request $request,$id)
{
    $vendor=User::findOrFail($id);
$validatedData=$request->validate(
    [
     'name'=>'string|required|max:255',
     'email' => 'required|string|email|max:255|unique:users,email,' . $id,
     'shop_name'=>'string|nullable|max:255',
     'username' => 'nullable|string|max:255|unique:users,username,' . $id,
     'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
     'phone' => 'nullable|string|min:10|max:15',
     'address' => 'nullable|string|max:255',
    //  'role' => 'required|in:user,vendor,admin',
    //  'status' => 'required|in:active,inactive',
     'vendor_info' => 'nullable|string',
     'vendor_join_date'=>'nullable|digits:4|integer|min:2024|max:'.date('Y'),
    ]
    );

     //Update User Photo

     if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if (!empty($vendor->photo) && file_exists(public_path($vendor->photo))) {
            unlink(public_path($vendor->photo));
        }
    
        $fileName = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('uploads/users/'), $fileName);
        $validatedData['photo'] = 'uploads/users/' . $fileName;
    }
    $vendor->update($validatedData);

  
     return redirect()->route('vendors.all')->with('success', 'Vendor updated successfully');

}

/**
 * Vendor Delete
 */
public function vendorDestroy($id)
{
    $vendor= User::findOrFail($id);
    if ($vendor->photo && file_exists(public_path($vendor->photo))) {
        unlink(public_path($vendor->photo));
    }

      // Check if the vendor has any associated products
      $products = $vendor->products;  // Assuming a relationship exists between User and Product (i.e., hasMany relation)

      // If products exist, delete all related multi images and products
      if ($products->isNotEmpty()) {
          foreach ($products as $product) {
              // Delete related multi images
              $product->multiimages()->delete();  // Assuming you have a relationship for multi images (multiImgs is a method)
  
              // Delete the product
              $product->delete();
          }
      }
    $vendor->delete();
    
    return redirect()->route('vendors.all')->with('success', 'Vendor deleted successfully.');
}

}