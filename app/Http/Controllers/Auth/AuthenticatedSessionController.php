<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegisteredNotification;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $user = $request->user();


        // Allow only 'user' role, deny access for others
         if ($user->role !== 'user') {
                Auth::logout();
                return back()->withErrors(['error' => 'Access denied. Only users are allowed to sign in.']);
            }
           

        // Check if the email is verified
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->route('verification.notice')->with('error', 'You must verify your email before logging in.');
        }
    
        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        $user=User::where('id',$request->id);
        $admins = User::where('role','admin')->get();
        // Notification::send($admins, new UserRegisteredNotification($user));
    
        return redirect('/');
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
