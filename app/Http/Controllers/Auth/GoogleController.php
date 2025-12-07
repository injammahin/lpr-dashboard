<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\InvitedUser;

use App\Models\User;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Google login failed.');
    }

    // Find invited record
    $invited = InvitedUser::where('email', $googleUser->getEmail())->first();

    // Not invited at all
    if (!$invited) {
        return redirect()->route('login')->with('error', 'You are not authorized to access this system.');
    }

    // Invited but inactive
    if (!$invited->is_active) {
        return redirect()->route('login')->with('error', 'Your access is disabled. Contact the administrator.');
    }

    // Invited but NOT YET accepted
    if (!$invited->accepted) {
        return redirect()->route('login')->with('error', 'Your invitation is not yet accepted. Please register using the invitation link.');
    }

    // Create/Register user
    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'password' => bcrypt(str()->random(16)),
        ]
    );

    auth()->login($user);

    return redirect()->route('dashboard.ui');
}



}

