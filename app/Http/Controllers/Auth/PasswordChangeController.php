<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Show the form to change password.
     */
    public function showChangeForm()
    {
        return view('auth.force-change-password');
    }

    /**
     * Update the user's password and remove the "must change" flag.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // 1. Update the password and flip the flag
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        // 2. Refresh the session to acknowledge the new password
        // This prevents being logged out immediately due to a password change
        Auth::logoutOtherDevices($request->password);

        // 3. Redirect to dashboard
        return redirect()->route('dashboard')->with('success', 'Account secured successfully! Welcome.');
    }
}
