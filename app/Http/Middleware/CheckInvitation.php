<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\InvitedUser;
use Symfony\Component\HttpFoundation\Response;

class CheckInvitation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next)
{
    if (!InvitedUser::where('email', $request->email)->where('accepted', true)->exists()) {
        return back()->withErrors(['email' => 'You are not invited to access this system.']);
    }

    return $next($request);
}

}
