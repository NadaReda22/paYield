<?php

namespace App\Http\Controllers\Admin;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AdminAuthenticatedController extends Controller
{
    /**
     * Display the login view.
     */

public function create(): View
{
   return view('admin.login');
}

  /**
     * Handle an incoming authentication request.
  */

public function store(LoginRequest $request): RedirectResponse
{

$request->authenticate();
$user=Auth::user();

if($user->role==='admin')
{$request->session()->regenerate();
return redirect('/admin/dashboard');
}

    Auth::logout(); 
   return back()->with('error','Access denied. Only admins are allowed to sign in.');


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
