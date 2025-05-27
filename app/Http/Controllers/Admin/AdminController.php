<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
   public function adminAllData()
   {
      $alladminuser = User::where('role','admin')->latest()->get();
    return view('admin.all_admin',compact('alladminuser'));
   }


   public function adminCreate()
   {
        $roles = Role::all();
    return view('admin.add_admin',compact('roles'));
   }


public function adminStore(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'string|required|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8',
        'username' => 'nullable|string|max:255|unique:users,username',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'phone' => 'nullable|string|min:10|max:15',
        'address' => 'nullable|string|max:255',
        'role_id' => 'required|exists:roles,id',
    ]);
    
    $role_id = $validatedData['role_id'];
    unset($validatedData['role_id']); // Remove it to avoid DB error
// dd($validatedData);
    // Hash the password
    $validatedData['password'] = bcrypt($validatedData['password']);

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $fileName = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('uploads/users/'), $fileName);
        $validatedData['photo'] = 'uploads/users/' . $fileName;
    }

    // Create user without role_id
    $user = User::create($validatedData);
    $user->role='admin';
    $user->save();

    // Assign role
    $role = Role::find($role_id);
    $user->assignRole($role->name);

    return redirect()->route('admins.all')->with('success', 'User created successfully');
}



   public function adminEdit($id)
   {
    $admin =User::findOrfail($id);
    return view('admin.edit_admin',compact('admin'));
   }


   public function adminUpdate(Request $request, $id)
   {
       $admin = User::findOrFail($id);
   
       $validatedData = $request->validate([
           'name' => 'string|required|max:255',
           'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        //    'password' => 'nullable|string|min:8',
           'username' => 'nullable|string|max:255|unique:users,username,' . $id,
           'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
           'phone' => 'nullable|string|min:10|max:15',
           'address' => 'nullable|string|max:255',
       ]);
   
       // Assign role explicitly (if needed)
       $validatedData['role'] = $admin->role;
   
       // Update User Photo
       if ($request->hasFile('photo')) {
           if (!empty($admin->photo) && file_exists(public_path($admin->photo))) {
               unlink(public_path($admin->photo));
           }
   
           $fileName = time() . '.' . $request->photo->extension();
           $request->photo->move(public_path('uploads/users/'), $fileName);
           $validatedData['photo'] = 'uploads/users/' . $fileName;
       }
   
       // Check if password is provided, then hash it
       if ($request->filled('password')) {
           $validatedData['password'] = bcrypt($request->password);
       } else {
           unset($validatedData['password']);
       }
   
       // Force fill and save
       $admin->update($validatedData);
   
       return redirect()->route('admins.all')->with('success', 'User updated successfully');
   }
   
   
   

/**
 * Admin Delete
 */
   public function adminDestroy($id)
   {
    $admin=User::findOrFail($id);

    //check if file exists to unlink and delete
    if($admin->photo && file_exists(public_path($admin->photo)))
    {
        unlink(public_path($admin->photo));
    }

    $admin->delete();

    return redirect()->route('admins.all')->with('success', 'User deleted successfully');
   }


/*------------------------------------------------------------------------------------------------------------------------*/
   /*-------------------------------------------Admin Dashboard----------------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------------------------*/

   public function index()
   {

     $date=date('d-m-y');
     $order_today=Order::where('order_date',$date)->sum('amount');

     $month=date('F');
     $order_month=Order::where('order_month',$month)->sum('amount');

     $year=date('Y');
     $order_year=Order::where('order_year',$date)->sum('amount');

     $vendors=User::where('status','active')->where('role','vendor')->get();
     $customers=User::where('status','active')->where('role','user')->get();

     $pending_order=Order::where('status', 'pending')->orderBy('id', 'DESC')->limit(10)->get();

       $vendor = Auth::id();
   

   
       return view('admin.index', compact('order_today','order_year','order_month','customers','vendors','pending_order'));
   }
   


   //Show Admin Profile 

   public function AdminProfile(){
       $id = Auth::user()->id;
       $admin_data = User::find($id);
       return view('admin.admin_profile',compact('admin_data'));
   }


   //Update Admin Profile

   public function AdminProfileUpdate(Request $request)
   {
       $id = Auth::user()->id;
       $admin_data = User::findOrFail($id);
   
       $validatedData = $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|email|unique:users,email,' . $admin_data->id,
           'phone' => 'required|string|max:20',
           'address' => 'required|string|max:255',
           'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
       ]);
   

       if ($request->hasFile('photo')) {
           $fileName = time() . '.' . $request->photo->extension();
           $request->photo->move(public_path('uploads/admins/'), $fileName);
           $validatedData['photo'] = 'uploads/admins/' . $fileName;
       }
   
       $admin_data->update($validatedData);
   
       return redirect()->back()->with('success', 'Profile Updated Successfully');
   }
   
   

   //Admin Change Password

   public function AdminChangePassword(){
       
       return view('admin.admin_change_password');
   }

   // Update Admin Password 

   public function AdminUpdatePassword(Request $request)
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

