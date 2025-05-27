<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
public function store(Request $request): RedirectResponse
{
    $user = $request->user();

    if ($user->hasVerifiedEmail()) {
        return redirect()->intended(
            $user->role === 'vendor' ? route('vendor.dashboard') : route('dashboard')
        );
    }

    $user->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
}

}
