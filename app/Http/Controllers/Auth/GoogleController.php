<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Check invite
        if (!Invite::where('email', $googleUser->email)->exists()) {
            abort(403, "You are not invited to access this system.");
        }

        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            ['name' => $googleUser->name]
        );

        Auth::login($user);

        return redirect('/dashboard-ui');
    }
}
