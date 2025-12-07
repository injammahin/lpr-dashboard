<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvitedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class InviteController extends Controller
{
    public function index()
    {
        return view('admin.invite.index', [
            'invited' => InvitedUser::latest()->get()
        ]);
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:invited_users,email'
        ]);

        $token = Str::random(40);

        $invite = InvitedUser::create([
            'email' => $request->email,
            'token' => $token
        ]);

        // Send email
        Mail::to($invite->email)->send(new \App\Mail\UserInvitationMail($invite));

        return back()->with('success', 'Invitation sent successfully!');
    }
}
