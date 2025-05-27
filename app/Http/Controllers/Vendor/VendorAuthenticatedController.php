<?php

namespace App\Http\Controllers\Vendor;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorRequestNotification;

class VendorAuthenticatedController extends Controller
{
    /**
     * Display the login view.
     */

public function create(): View
{
   return view('vendor.login');
}

  /**
     * Handle an incoming authentication request.
  */

public function store(LoginRequest $request): RedirectResponse
{

$request->authenticate();

$user=Auth::user();

if($user->role==='vendor')
{
    $request->session()->regenerate();

    $vendor=User::where('id',$request->id);
    // $admins = User::where('role','admin')->get();
    // Notification::send($admins, new VendorRequestNotification($vendor));
    return redirect('/vendor/dashboard');
}

    Auth::logout(); 
   return redirect()->back()->with('error','Access denied. Only vendors are allowed to sign in.');


}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request ): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

}
