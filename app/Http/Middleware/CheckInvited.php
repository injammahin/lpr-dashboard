<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\InvitedUser;

class CheckInvited
{
    public function handle($request, Closure $next)
    {
        $email = $request->email;

        // Only allow login if the email is invited AND accepted
        if (!InvitedUser::where('email', $email)->where('accepted', true)->exists()) {
            return back()->withErrors([
                'email' => 'You are not invited to access this system.'
            ]);
        }

        return $next($request);
    }
}
